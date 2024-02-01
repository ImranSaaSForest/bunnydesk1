@php
    use Filament\Support\Facades\FilamentView;

    $id = $getId();
    $isConcealed = $isConcealed();
    $isDisabled = $isDisabled();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $prefixActions = $getPrefixActions();
    $prefixIcon = $getPrefixIcon();
    $prefixLabel = $getPrefixLabel();
    $suffixActions = $getSuffixActions();
    $suffixIcon = $getSuffixIcon();
    $suffixLabel = $getSuffixLabel();
    $statePath = $getStatePath();
@endphp
<x-dynamic-component wire:ignore
    :component="$getFieldWrapperView()"
    :field="$field"
>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.0.3/resumable.min.js"
    integrity="sha512-OmtdY/NUD+0FF4ebU+B5sszC7gAomj26TfyUUq6191kbbtBZx0RJNqcpGg5mouTvUh7NI0cbU9PStfRl8uE/rw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .load-wrapp:last-child {
  margin-right: 0;
}

.line {
  display: inline-block;
  width: 10px;
  height: 6px;
  border-radius: 15px;
  background-color: white;
}
.load-3 .line:nth-last-child(1) {
  animation: loadingC 0.6s 0.1s linear infinite;
}
.load-3 .line:nth-last-child(2) {
  animation: loadingC 0.6s 0.2s linear infinite;
}
.load-3 .line:nth-last-child(3) {
  animation: loadingC 0.6s 0.3s linear infinite;
}
@keyframes loadingC {
  0 {
    transform: translate(0, 0);
  }
  50% {
    transform: translate(0, 15px);
  }
  100% {
    transform: translate(0, 0);
  }
}


    </style>

    <x-filament::input.wrapper
        :disabled="$isDisabled"
        :inline-prefix="$isPrefixInline"
        :inline-suffix="$isSuffixInline"
        :prefix="$prefixLabel"
        :prefix-actions="$prefixActions"
        :prefix-icon="$prefixIcon"
        :suffix="$suffixLabel"
        :suffix-actions="$suffixActions"
        :suffix-icon="$suffixIcon"
        :valid="! $errors->has($statePath)"
        x-data="{}"

    >
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }" id="fileContainer">
        <div @click="$refs.open.click()" id="drag"
            class=" border-[#D1D5DB] border   cursor-pointer text-[#4B5563] rounded-md  py-2 w-full  text-center shadow-[0px_1px_3px_0px_rgba(0,0,0,0.10)]  block">
            Drag & Drop your files or <span class=" underline">Browse</span>
        </div>
        <!-- Interact with the `state` property in Alpine.js -->
                <x-filament::input
                    x-ref="open"
                    id="file-upload"
                    class="hidden py-1.5 shadow-[0px_1px_3px_0px_rgba(0,0,0,0.10)]"
                    :attributes="
                        \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                            ->merge([

                                'autofocus' => $isAutofocused(),
                                'disabled' => $isDisabled,
                                'inlinePrefix' => $isPrefixInline && (count($prefixActions) || $prefixIcon || filled($prefixLabel)),
                                'inlineSuffix' => $isSuffixInline && (count($suffixActions) || $suffixIcon || filled($suffixLabel)),
                                'placeholder' => $getPlaceholder(),
                                'required' => $isRequired() && (! $isConcealed),
                                'type' => 'file',
                                'x-model' . ($isLiveDebounced() ? '.debounce.' . $getLiveDebounce() : null) => 'state',
                            ], escape: false)
                    "
                />
        </div>
        <div id="fileUploadSuccess"
        style="display: none"
         class="p-2 flex justify-between bg-green-500 rounded-lg text-white">
        </div>
    </x-filament::input.wrapper>
    <div style="display: none" class="progress mt-3" style="height: 6px" wire:ignore>
        <div style="background: linear-gradient(to left, #F2709C, #FF9472);box-shadow: 0 3px 3px -5px #F2709C, 0 2px 5px #F2709C;	border-radius: 20px;
    " class="progress-bar py-[6px] progress-bar-striped  progress-bar-animated px-2 text-white" role="progressbar"
            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">75%</div>
    </div>

    <script>
        var r = new Resumable({
            target: '/upload_doc',
            chunkSize: 1*1024*1024, // 1 MB per chunk, adjust as needed
            query: {_token: '{{ csrf_token() }}'},
            testChunks: false,
            throttleProgressCallbacks: 1,
            maxFileSize:500*1024*1024
            // minFileSize:
        });
        console.log(r);

        r.assignBrowse(document.getElementById('file-upload'));
        r.assignDrop(document.getElementById('drag'));
        r.on('fileAdded', function(file){
          console.log(file);
            showProgress();
            // Start uploading once a file is added
            r.upload();
        });


        r.on('fileProgress', function(file) {
            updateProgress(Math.floor(file.progress() * 100));
    // Send the total number of chunks with each chunk
  });
  r.on('fileSuccess', function (file, response) {
      response = JSON.parse(response)
      console.log(response);
     let extension=response.mime_type.split("-")
      let filename=response.path + '/' + response.name
      $('#fileContainer').hide();
      $('#fileUploadSuccess').show();
      $('#fileUploadSuccess').html(file.fileName);
      $('#fileUploadSuccess').append('<span>Upload Complete</span>');
      @this.dispatch('setFileName', { filename:filename,originalname:file.fileName});

    // trigger when file upload complete
    // r.removeFile(file);


    });
        r.on('fileError', function(file, message){
            // Handle errors
            console.log(message);

        });

        let progress = $('.progress');

  function showProgress() {
    progress.find('.progress-bar').css('width', '0%');
    progress.find('.progress-bar').html('0%');
    progress.show();
  }

  function updateProgress(value) {

    progress.find('.progress-bar').css('width', `${value}%`)
    progress.find('.progress-bar').html(`${value}%`)
    progress.find('.progress-bar').addClass('bg-green-500');

    if (value === 100) {
        hideProgress()
    }
  }

  function hideProgress() {

    progress.hide();
  }


    </script>
</x-dynamic-component>
