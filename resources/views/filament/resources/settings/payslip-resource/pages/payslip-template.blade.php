<x-filament-panels::page>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    {{-- @vite('css/app.css') --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <div class="flex gap-4">

        <form wire:submit='createTemplate'>
            <div>
                <div class="grid gap-y-2 mb-2 ">
                    <div class="flex items-center justify-between gap-x-3">
                        <label class=" inline-flex items-center gap-x-3" for="data.name">
                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                Name
                                <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                            </span>
                        </label>
                    </div>
                    <div class="grid gap-y-2 ">
                        <div
                            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white focus-within:ring-2 dark:bg-white/5 ring-gray-950/10 focus-within:ring-primary-600 dark:ring-white/20 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                            <div class="min-w-0 flex-1">
                                <input
                                    class="fi-input block w-full border-none bg-transparent py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 ps-3 pe-3"
                                    id="templateName" maxlength="255" type="text" wire:model="templateName">
                            </div>
                        </div>
                        <div class="text-red-600">@error('templateName') {{ $message }} @enderror</div>
                    </div>
                </div>
                <div class="grid gap-y-2 mb-2">
                    <div class="flex items-center justify-between gap-x-3">
                        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="data.company_id">
                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                Company
                                <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                            </span>
                        </label>
                    </div>
                    <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white focus-within:ring-2 dark:bg-white/5 ring-gray-950/10 focus-within:ring-primary-600 dark:ring-white/20 dark:focus-within:ring-primary-500 fi-fo-select">
                    <select
                        class="fi-input block w-full border-none py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 bg-white/0 ps-3 pe-3"
                        name="" id="" wire:model='companyId'>
                        <option value="">Select an option</option>
                        @foreach ($companies as $company)
                        <option value="{{$company->id}}">{{$company->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="text-red-600">@error('companyId') {{ $message }} @enderror</div>

                </div>
                <div class="grid gap-y-2 mb-2">
                    <div class="flex items-center justify-between gap-x-3">
                        <label class=" inline-flex items-center gap-x-3" for="data.name">
                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                Description
                            </span>
                        </label>
                    </div>
                    <div class="grid gap-y-2 ">
                        <div
                            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white focus-within:ring-2 dark:bg-white/5 ring-gray-950/10 focus-within:ring-primary-600 dark:ring-white/20 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                            <div class="min-w-0 flex-1">
                                <input
                                    class="fi-input block w-full border-none bg-transparent py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 ps-3 pe-3"
                                    id="description" maxlength="255" type="text" wire:model="description">
                            </div>
                            <div class="text-red-600">@error('description') {{ $message }} @enderror</div>

                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-x-3">
                    <label class=" inline-flex items-center gap-x-3" for="data.name">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                            Content
                            <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>

                        </span>
                    </label>
                </div>

                <div wire:ignore>
                    {{-- <trix-editor x-on:trix-change="$wire.content = $event.target.value "></trix-editor> --}}
                    <div id="ckeditor">

                    </div>
                    <input id="editor" type="hidden" name="content" value="{{ $content }}">
                    {{-- <trix-editor input="editor"></trix-editor> --}}
                    {{-- <script>
                        function setContent(){
            var trixEditor = document.getElementById("editor")

            @this.set('content', trixEditor.getAttribute('value'))


        }


                    </script> --}}
                </div>
                <div class="text-red-600">@error('content') {{ $message }} @enderror</div>
            </div>



            <button onclick="setContent()" type="submit"
                class="bg-blue-600 px-2 py-1 mt-2 rounded text-white w-fit">{{$currentAction=='edit'?'Save':'Create'}}</button>
            <a class="px-2 py-1 mt-2 rounded border border-gray-300" href="/settings/payslips">Cancel</a>
        </form>




        {{-- CUSTOM VARIABLE SECTION --}}
        @livewire('custom-variable')
        {{-- PLACEHOLDER SECTION --}}
        <div class="w-[70%] mt-2">
            <p class="font-bold font-lg ">Payslip Placeholders</p>
            <p class="">You can insert these into the email and they will be replaced with the actual
                values when the payslip is send.</p>
            @if ($payrollVariables)

            @foreach ($payrollVariables as $payrollVariable)
            <div class="flex justify-between px-2 py-1 mt-2  border-b border-gray-400">
                <div class="text-amber-600  variable" onclick="appendData('{'+'{{$payrollVariable->name}}'+'}')" {{--
                    @click="document.querySelector('#ckeditor').append('{'+'{{$payrollVariable->name}}'+'}');" --}}
                    wire:key='{{$payrollVariable->id}}'>
                    {{$payrollVariable->name}}</div>
                @if ($payrollVariable->custom!=0)
                <div class="flex gap-2 item-center">
                    <x-heroicon-m-pencil-square class="w-5 text-amber-500 cursor-pointer"
                        @click="$dispatch('editCustomVariable', { id: '{{$payrollVariable->id}}' })" />
                    <x-heroicon-m-trash class="w-5 text-red-500 cursor-pointer"
                        wire:click='deleteCustomVariable({{$payrollVariable->id}})' />
                </div>
                @endif
            </div>
            @endforeach
            @endif


            <div class="bg-blue-600 px-2 py-1 mt-2 rounded text-white w-fit"
                @click="$dispatch('open-modal', { id: 'edit-user' })">Add Custom variables</div>
        </div>

    </div>
    <script>
    let editor;
     ClassicEditor

     .create( document.querySelector( '#ckeditor' ) )
     .then( newEditor => {

     editor = newEditor;

     })

     .catch( error => {
     console.error( error );
     } );


// append variable
function appendData(data){

editor.model.change( writer => {
editor.model.insertContent( writer.createText( data) );
} );
}
// ...set content to livewire
function setContent(){
const edxitorData = editor.getData();
@this.set('content', edxitorData)
}
document.addEventListener('livewire:initialized', () => {
    // set content if its edit
if(@this.get('content')){
    editor.setData(@this.get('content'))
}
// skip editor morphing
// Livewire.hook('morph.removing', ({ el, component, skip }) => {
//  skip()
// })
})

    </script>
</x-filament-panels::page>







