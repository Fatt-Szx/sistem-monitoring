@include('layouts.head')
<body>

  @include('layouts.header')
  @include('layouts.mnavbar')
  @include('layouts.sidebar')
  @include('layouts.content')
  @include('layouts.footer')

  {{-- Tempatkan di sini, hanya sekali --}}
  @stack('scripts')

</body>
