<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductQuestion;
use App\Notifications\GenericNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'question' => ['required', 'string', 'max:500'],
        ]);

        // Cegah spam: max 3 pertanyaan per produk per user
        $count = ProductQuestion::where('product_id', $product->id)
            ->where('user_id', auth()->id())
            ->count();

        if ($count >= 3) {
            return back()->with('error', 'Kamu sudah bertanya 3 kali untuk produk ini. Tunggu jawaban dari mitra.');
        }

        $question = ProductQuestion::create([
            'product_id' => $product->id,
            'user_id'    => auth()->id(),
            'question'   => $data['question'],
        ]);

        $user = auth()->user();
        $user->addPoints(1, 'question', 'Bertanya tentang ' . $product->name, $product);
        $user->checkBadges($question);

        // Notify partner
        if ($product->partner?->user) {
            $product->partner->user->notify(new GenericNotification(
                "Ada pertanyaan baru untuk {$product->name} dari {$user->name}.",
                '❓',
                route('partner.questions.index')
            ));
        }

        return back()->with('success', 'Pertanyaan terkirim. Mitra akan menjawab segera.');
    }
}
