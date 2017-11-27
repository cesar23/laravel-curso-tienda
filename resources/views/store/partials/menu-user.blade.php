@if(Auth::check())
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			<i class="fa fa-user"></i> {{ Auth::user()->user }} <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
{{--			<li><a href="{{ route('logout') }}">Finalizar sesión</a></li>--}}
			<li><a href="{{ route('logout') }}"
			   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
				Logout
				</a></li>

			<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
				{{ csrf_field() }}
			</form>
		</ul>
	</li>
@else
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			<i class="fa fa-user"></i> <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{ route('login') }}">Iniciar sesión</a></li>
			<li>
				<a href="{{ route('register') }}">Registrarse</a></li>
		</ul>
	</li>
@endif