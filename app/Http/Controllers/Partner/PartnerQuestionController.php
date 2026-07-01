<?php
namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\ProductQuestion;
use App\Notifications\GenericNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnerQuestionController extends Controller
{
    private function partner()
    {
        return auth('partner')->user()->partner;
    }

    public function index(): View
    {
        $partner = $this->partner();
        $productIds = $partner->products()->pluck('id');

        $questions = ProductQuestion::with(['user', 'product'])
            ->whereIn('product_id', $productIds)
            ->latest()
            ->paginate(20);

        return view('partner.questions', [
            'partner'   => $partner,
            'questions' => $questions,
            'storeName' => config('catalog.store_name'),
        ]);
    }

    public function answer(Request $request, ProductQuestion $question): RedirectResponse
    {
        $partner = $this->partner();

        // Ensure question belongs to partner's product
        abort_if($question->product->partner_id !== $partner->id, 403);

        $data = $request->validate([
            'answer' => ['required', 'string', 'max:1000'],
        ]);

        $question->update([
            'answer'      => $data['answer'],
            'answered_at' => now(),
            'answered_by' => auth('partner')->id(),
        ]);

        // Notify member
        if ($question->user) {
            $question->user->notify(new GenericNotification(
                "Pertanyaanmu tentang {$question->product->name} telah dijawab oleh mitra.",
                '💬',
                route('catalog.show', $question->product->slug)
            ));
        }

        return back()->with('success', 'Jawaban terkirim.');
    }
}
