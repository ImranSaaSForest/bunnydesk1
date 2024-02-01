<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <div class="flex justify-end">
        <x-filament::button size="lg" color="primary" @click="$dispatch('open-modal', { id: 'edit-user' })">
            Send Email
        </x-filament::button>
    </div>
    {{ $this->infolist }}
{{-- @dd($value) --}}
    {{-- nav bar start --}}
   <nav class="fi-tabs flex max-w-full gap-x-1 overflow-x-auto mx-auto rounded-xl bg-white p-1 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
        role="tablist" x-data="{
                value: @entangle('value'),
                clickbutton: 'fi-tabs-item group flex items-center gap-x-2 rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75 fi-active fi-tabs-item-active bg-gray-50 dark:bg-white/5',
                defaultbutton: 'fi-tabs-item group flex items-center gap-x-2 rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75 hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5 dark:bg-white/5',
                style: 'fi-tabs-item-label transition duration-75 dark:text-primary-400',
                styleDefault: 'text-primary-600',
            }">
            {{-- resume button --}}
        @if($resume_path!='empty')
        <button type="button" :class="value ==1? clickbutton : defaultbutton" aria-selected="aria-selected" role="tab"
            wire:click="Resume({{1}})" @click="value='1'"  wire:key="{{2}}">
            <span :class="value == 1 ? styleDefault : style" >
                Resume
            </span>
        </button>
        @endif

        {{-- resume button end --}}
        {{-- notes button  --}}
        <button type="button" :class="value ==2 ? clickbutton : defaultbutton" role="tab" wire:click="Notes({{2}})"
            @click="value=2"  wire:key="{{2}}">
            <span :class="value == 2 ? styleDefault : style">
                Notes
            </span>
        </button>
        {{-- notes button end --}}
        {{--  visa button --}}
        @if($visa_path!='empty')
        <button type="button" :class="value==3 ? clickbutton : defaultbutton" aria-selected="aria-selected" role="tab"
        @click="value=3" wire:key="{{3}}" wire:click="Visa({{3}})" >
            <span :class="value == 3 ? styleDefault : style">
                Visa
            </span>
        </button>
        @endif {{-- visa button end --}}

    </nav>
    {{-- nav bar end --}}

    {{-- resume view start --}}
    @if($value==1)
    <div class="">
        <embed src="/storage/{{$resume_path}}#sidebar=0&toolbar=0" type="application/pdf" width="100%" height="600px" />
        {{-- <iframe src="https://docs.google.com/viewer?url=http://127.0.0.1:8000/dummy.pdf&embedded=true"
            style="width:600px; height:500px;" frameborder="0"></iframe> --}}
    </div>
    <object data="sample.pdf" type="application/pdf" width="100%" height="600">
        <div>You can download the PDF file
                <span style="color:blue;text-decoration: underline;"><a href="/storage/{{$resume_path}}">Click here</a></span>.</div>
    </object>
    @endif
    {{-- resume view end --}}

    {{-- post notes --}}
    @if($value==2)
    <form wire:submit="create">
        {{ $this->form }}
        <div class="mt-4 gap-5">
            <x-filament::button size="lg" color="warning" type="submit">
                Post
            </x-filament::button>
            {{-- <x-filament::button size="lg" color="gray">
                Cancel
            </x-filament::button> --}}
        </div>

    </form>
    @endif

    @if($value==3)
     <img src="/storage/{{$visa_path}}" alt="" width="100%" height="600px">
    @endif
    <div>
        @if ($value == 2)
        @foreach ($candidate_notes as $candidate_note)
        {{-- @dd($candidate_note) --}}
        <div class="flex gap-x-5 mt-3">
            <div>
                <img src="{{$candidate_note->user->employee->profile_picture_url}}" alt="" class="rounded-full w-10 h-10">
            </div>
            <div>
                <h2 class=" font-medium">{{ $candidate_note->user->name }}</h2>
                <?php
                            $numberOfDays = 1; // Change this to the number of days you want to go back
                            $currentDate = date('Y-m-d'); // Get the current date and time in 'Y-m-d H:i:s' format
                            $previousDate = date('Y-m-d', strtotime("-$numberOfDays days", strtotime($currentDate)));
                            $currentTimezone = date_default_timezone_get();
                           // dd($currentTimezone);
                            // $createdAtInCurrentTimezone = $candidate_note->created_at->setTimezone('Asia/Kolkata');
                            $Created_day = '';
                            if ($currentDate == date('Y-m-d', strtotime($candidate_note->created_at))) {
                                $Created_day = 'Today';
                            } elseif ($previousDate == date('Y-m-d', strtotime($candidate_note->created_at))) {
                                $Created_day = 'Yesterday';
                            } else {
                                $Created_day = date('D-m-Y ', strtotime($candidate_note->created_at));
                            }
                            ?>
                <span class=" text-[#6B7280]">{{ $Created_day }}</span>
                <div>
                    <p class=" pt-2">{{ $candidate_note->notes }}</p>
                </div>
            </div>
        </div>
        @endforeach
        @endif

    </div>
    {{-- post notes end --}}

    {{-- email send form --}}
<div>

</div>
    <x-filament::modal id="edit-user" width="4xl"  height="100%" >


        <div class="">
            <form action="send_email.php" method="post"  wire:submit='createTemplate'>
<div id="editorValue" data-content="{{$sms}}" ></div>

                <div class="grid gap-y-2 mb-6 grid-cols-2 gap-x-5  ">
                    <div class="">
                        <label class=" inline-flex items-center gap-x-3" for="data.name">
                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                Choose Template
                                <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                            </span>
                        </label>
                        <select oninput="setContent();" id="fruit" name="fruit" wire:model.live="template"  class="w-full rounded-md" required>
                            <option value="all">Select Template</option>
                            @foreach ($offer_template as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach

                        </select>
                        {{-- <span>{{$templates->description}}</span> --}}
                    </div>
                    <div>

                        <div class="flex items-center justify-between gap-x-3">
                            <label class=" inline-flex items-center gap-x-3" for="data.name">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Email Subject
                                    <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                                </span>
                            </label>
                        </div>
                        <div class="grid gap-y-2 ">
                            <div
                                class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white focus-within:ring-2 dark:bg-white/5 ring-gray-950/10 focus-within:ring-primary-600 dark:ring-white/20 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                                <div class="min-w-0 flex-1">
                                        {{-- @dd($item) --}}

                                        <input value="{{$templates?$templates->description : '
                                        '}}"
                                        class="fi-input  block w-full border-none bg-transparent py-2
                                        text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 ps-3 pe-3"
                                        id="templateName" maxlength="255" type="text" wire:model="templateName" required>

                                </div>
                            </div>
                        </div>



                    </div>
                </div>


                <div wire:ignore>

                    <div id="ckeditor">


                    </div>
                    {{-- @dd($item->content) --}}
                    <input  id="editor" type="hidden" name="content" value="{{$sms}}">

                </div>





                    <div class="mt-4 space-x-4">
                        {{-- <x-filament::button size="lg" color="warning" wire:click="send()">
                            Send
                        </x-filament::button>

                        <x-filament::button size="lg" color="gray">
                            Cancel
                        </x-filament::button> --}}

                        <button  type="submit" class="bg-vlue-600 px-4 py-1 mt-4 rounded text-white w-fit" >Send</button>
                        <a class="px-2 py-1 mt-2 rounded border border-gray-300" href="">Cancel</a>

                    </div>

            </form>

    </x-filament::modal>
    </div>

    <script>
    let editor;
    let ele1=document.getElementById('editorValue');

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
if(@this.get('sms')){
    // console.log(@this.get('sms'));
    editor.setData(@this.get('sms'))
}
// skip editor morphing
// Livewire.hook('morph.removing', ({ el, component, skip }) => {
//  skip()
// })
})
function setContent(){
    setTimeout(() => {
        let val1=ele1.getAttribute('data-content')
console.log(val1);
    if(val1){
    editor.setData(val1)
}
    }, 1000);


}
        </script>
    {{-- email send form end --}}
</x-filament-panels::page>
