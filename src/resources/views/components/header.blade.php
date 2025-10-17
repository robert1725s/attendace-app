{{-- ヘッダーコンポーネント --}}
<header class="header">
    <div class="header__container">
        {{-- ロゴ --}}
        <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="header__logo">

        {{-- ナビゲーション --}}
        <nav class="header__nav">
            @auth
                @if (auth()->user()->is_admin)
                    {{-- 管理者用ナビゲーション --}}
                    <a href="/admin/attendances/list" class="header__nav-link">勤怠一覧</a>
                    <a href="/admin/staff/list" class="header__nav-link">スタッフ一覧</a>
                    <a href="/admin/stamp_correction_request/list" class="header__nav-link">申請一覧</a>
                @else
                    {{-- 一般ユーザ用ナビゲーション --}}
                    <a href="/attendance" class="header__nav-link">勤怠</a>
                    <a href="/attendance/list" class="header__nav-link">勤怠一覧</a>
                    <a href="/stamp_correction_request" class="header__nav-link">申請</a>
                @endif
                <form class="header__nav-form" action="/logout" method="post">
                    @csrf
                    <button type="submit" class="header__nav-link header__nav-link--button">ログアウト</button>
                </form>
            @endauth
        </nav>
    </div>
</header>
