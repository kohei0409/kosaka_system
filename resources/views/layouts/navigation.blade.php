<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
    <!-- サイドバー -->
    <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
        <div class="p-4">
            <h2 class="text-lg font-semibold">ユーザ管理</h2>
            <ul class="mt-4 space-y-2">
                @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'Admin']))
                <li>
                    <a href="{{ route('users.index') }}"
                       class="block py-2 px-4 hover:bg-gray-700 rounded text-sm font-medium">
                        ユーザー一覧
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.create') }}"
                       class="block py-2 px-4 hover:bg-gray-700 rounded text-sm font-medium">
                        ユーザー登録
                    </a>
                </li>
                <li>
                    <a href="{{ route('salescourses.index') }}"
                       class="block py-2 px-4 hover:bg-gray-700 rounded text-sm font-medium">
                        営業コース
                    </a>
                </li>
                <li>
                    <a href="{{ route('customers.index') }}"
                       class="block py-2 px-4 hover:bg-gray-700 rounded text-sm font-medium">
                        得意先データ
                    </a>
                </li>
                <li>
                    <a href="{{ route('commodities.index') }}"
                       class="block py-2 px-4 hover:bg-gray-700 rounded text-sm font-medium">
                        商品データ
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.create') }}"
                       class="block py-2 px-4 hover:bg-gray-700 rounded text-sm font-medium">
                        受注データ
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </aside>

    <!-- メインコンテンツ -->
    <div class="flex-1">
        <!-- ナビゲーションバー -->
        <nav class="bg-black dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- ロゴ -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}">
                                <x-application-logo
                                    class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"/>
                            </a>
                        </div>

                        <!-- ナビゲーションリンク -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- 設定ドロップダウン -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                                     onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </nav>

        <!-- メインページ内容 -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>
</div>

<style>
    aside {
        min-width: 10rem; /* サイドバーの固定幅 */
        height: 100vh; /* サイドバーを画面いっぱいに */
        position: fixed; /* 固定位置 */
        top: 0;
        left: 0;

    }


    .flex-1 {
        overflow-x: auto; /* 横スクロールのサポート */
        padding-left:90px;

    }

    .text-sm {
        font-size: 0.875rem; /* フォントサイズの調整 */
    }
</style>
