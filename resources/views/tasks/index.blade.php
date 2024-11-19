<x-app-layout>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>

        <div id="table"></div>
    </x-slot>
    @section('js')
     
        <script>
            $('table').dataTable({
                paginate: false,
                scrollY: 300
            });
        </script>
    @endsection
</x-app-layout>
