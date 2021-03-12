<div class="py-6 px-16">
    <div class="text-lg text-gray-600 leading-7 font-semibold">
        Zusammenkünfte für die Öffentlichkeit
    </div>
    <div class="mt-2">

{{--        <div class="flex">--}}
{{--            <div class="flex items-center justify-center text-red-700">--}}
{{--                <x-icon.pdf-file class="w-5 h-5" />--}}
{{--            </div>--}}
{{--            <div class="flex-1 flex items-center justify-between">--}}
{{--                <div class="flex-1 px-3 py-1 text-base text-gray-600 font-sans">--}}
{{--                    Erstellt am 20. Januar 2021--}}
{{--                </div>--}}
{{--                <div class="pr-4 flex items-center space-x-2">--}}
{{--                    <button class="text-gray-400 rounded-full bg-transparent hover:text-gray-600 inline-flex justify-center items-center">--}}
{{--                        <x-icon.hyperlink class="w-5 h-5"/>--}}
{{--                    </button>--}}
{{--                    <button class="text-indigo-400 rounded-full bg-transparent hover:text-indigo-600 inline-flex justify-center items-center">--}}
{{--                        <x-icon.download-circle class="w-5 h-5"/>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <button type="button" wire:click="generatePdf" class="mt-4 flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Generieren
        </button>

    </div>
</div>
