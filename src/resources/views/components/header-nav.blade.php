{{-- ヘッダーのロゴとナビゲーションを表示するコンポーネント --}}
<div class="header__logo">
    <a href="/" class="header__logo-link">
        <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="header__logo-image">
    </a>
</div>

<nav class="header__nav">
    @auth
        @if(auth()->user()->is_admin)
            {{-- 管理者用ナビゲーション --}}
            <a href="{{ route('admin.attendance.index') }}" class="header__nav-link">勤怠一覧</a>
            <a href="{{ route('admin.staff.index') }}" class="header__nav-link">スタッフ一覧</a>
            <a href="{{ route('admin.correction.index') }}" class="header__nav-link">申請一覧</a>
        @else
            {{-- 一般ユーザ用ナビゲーション --}}
            <a href="{{ route('user.attendance.stamp') }}" class="header__nav-link">勤怠</a>
            <a href="{{ route('user.attendance.index') }}" class="header__nav-link">勤怠一覧</a>
            <a href="{{ route('user.correction.index') }}" class="header__nav-link">申請</a>
        @endif
        <form class="header__nav-form" action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit" class="header__nav-link header__nav-link--button">ログアウト</button>
        </form>
    @endauth
</nav>

<style>
    /* ヘッダー全体スタイル */
    .header {
        background-color: #000;
        padding: 15px 0;
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* ヘッダーロゴスタイル */
    .header__logo {
        display: flex;
        align-items: center;
    }

    .header__logo-link {
        display: flex;
        align-items: center;
    }

    .header__logo-image {
        height: 24px;
    }

    /* ヘッダーナビゲーションスタイル */
    .header__nav {
        display: flex;
        gap: 30px;
        align-items: center;
    }

    .header__nav-form {
        margin: 0;
    }

    .header__nav-link {
        color: #ffffff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: opacity 0.3s;
    }

    .header__nav-link:hover {
        opacity: 0.7;
    }

    .header__nav-link--button {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        font-family: inherit;
    }
</style>
