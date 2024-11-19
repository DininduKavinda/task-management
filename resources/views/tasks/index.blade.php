<x-app-layout>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>

        <div>
            <table id="tblData" class="display">
                <thead>
                    <tr>
                        <th>title</th>

                    </tr>
                </thead>

            </table>
        </div>
        @section('js')
            <script src="resource/js/tasks.js"></script>
        @endsection
    </x-slot>

</x-app-layout>
