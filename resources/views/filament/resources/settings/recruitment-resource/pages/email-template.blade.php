<x-filament-panels::page>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <div class="">

        <form wire:submit='createTemplate'>
            <div>
                <div class="grid gap-y-2 mb-2">
                    <div class="flex items-center justify-between gap-x-3">
                        <label class=" inline-flex items-center gap-x-3" for="data.name">
                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                Subject
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
                                    id="templateName" maxlength="255" type="text" wire:model="templateName" >
                            </div>
                        </div>
                        <div class="text-red-600">@error('templateName') {{$message}} @enderror</div>
                    </div>
                </div>

                <div class="grid gap-y-2 mb-2">
                    <div class="flex items-center justify-between gap-x-3">
                        <label class=" inline-flex items-center gap-x-3" for="data.name">
                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                Description
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
                                    id="description" maxlength="255" type="text"  wire:model="description" >
                            </div>

                        </div>
                        <div class="text-red-600">@error('description') {{ $message }} @enderror</div>
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

                    <div id="ckeditor">


                    </div>
                    <input id="editor" type="hidden" name="content" value="{{ $content }}">
                </div>
                <div class="text-red-600">@error('content') {{ $message }} @enderror</div>
            </div>

            <div class="border border-gray-300 rounded-md mt-6 p-4 bg-white">

                <div>
                    <h1 class="font-bold text-2xl">Email Placeholder</h1>
                </div>
                <div class="grid grid-cols-3 gap-x-3 gap-y-4 mt-3">
                    @foreach ($emailVariables as $emailVariable)
                    <h1 class="cursor-pointer"  onclick="appendData('{'+'{{$emailVariable}}'+'}')">{{$emailVariable}}</h1>
                @endforeach
                </div>



            </div>

            <button   type="submit" onclick="setContent()"
                class="bg-[#B39800] px-2 py-1 mt-4 rounded text-white w-fit">{{$currentAction=='edit'?'Save':'Create'}}</button>
            <a class="px-2 py-1 mt-2 rounded border border-gray-300" href="/settings/payslips">Cancel</a>
        </form>




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
    document.addEventListener('livewire:initialized', () => {
    // set content if its edit
    if(@this.get('content')){
        editor.setData(@this.get('content'))
    }

})
function setContent(){
const edxitorData = editor.getData();
@this.set('content', edxitorData)
}
// append variable
function appendData(data){

editor.model.change( writer => {
editor.model.insertContent( writer.createText( data) );
} );
}
    </script>

</x-filament-panels::page>
