<nav class="flex items-center justify-between h-16">
                <div>
                    <a href="{{ route('home') }}">
                       <x-forum.logo />
                    </a>
                </div>

                <div class="flex gap-4">
                    <a href="#" class="text-sm font-semibold">Foro</a>
                    <a href="#" class="text-sm font-semibold">Blog</a>
                </div>

                <div>
                    @auth
                        <span class="text-sm font-semibold mr-4">Hola, {{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-semibold hover:underline">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold">Log in &rarr;</a>
                    @endauth
                </div>
 </nav>