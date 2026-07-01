@php $active = $activeNav ?? ''; @endphp
<header class="topbar">
    <div class="topbar-inner">
        <a href="{{ route('landing') }}" class="topbar-brand">{{ $storeName ?? config('catalog.store_name') }}</a>
        <nav class="topbar-nav">
            <a href="{{ route('catalog.index') }}" class="{{ $active === 'katalog' ? 'active' : '' }}">Katalog</a>
            <a href="{{ route('catalog.lookbook') }}" class="{{ $active === 'lookbook' ? 'active' : '' }}">Lookbook</a>
            <a href="{{ route('articles.index') }}" class="{{ $active === 'editorial' ? 'active' : '' }}">Editorial</a>
            <a href="{{ route('community.index') }}" class="{{ $active === 'komunitas' ? 'active' : '' }}">Komunitas</a>
            <a href="{{ route('partners.index') }}" class="{{ $active === 'mitra' ? 'active' : '' }}">Toko Mitra</a>
            <a href="{{ route('catalog.about') }}" class="{{ $active === 'tentang' ? 'active' : '' }}">Tentang</a>
            <a href="{{ route('web-report.create') }}" style="font-size:12px;color:var(--accent);">Laporkan</a>
            <a href="{{ route('search.index') }}" title="Cari Produk" style="font-size:16px;">🔍</a>
        </nav>
        <div class="topbar-auth">
            @auth
                <a href="{{ route('wishlist.index') }}" class="btn-login">♥ Wishlist</a>
                <form method="POST" action="{{ route('member.logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-login" style="border:0;cursor:pointer;background:none;font-family:inherit;">Logout</button>
                </form>
            @else
                <a href="{{ route('member.login') }}" class="btn-login">Login</a>
                <a href="{{ route('member.register') }}" class="btn-register">Daftar</a>
            @endauth
        </div>
    </div>
</header>
