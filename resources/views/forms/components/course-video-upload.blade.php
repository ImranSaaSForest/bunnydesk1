<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.0.3/resumable.min.js"
        integrity="sha512-OmtdY/NUD+0FF4ebU+B5sszC7gAomj26TfyUUq6191kbbtBZx0RJNqcpGg5mouTvUh7NI0cbU9PStfRl8uE/rw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">

            Videos<!--[if BLOCK]><![endif]--><sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
             <!--[if ENDBLOCK]><![endif]-->
        </span>
        @if (session()->get('videos'))
        <div>

            @foreach (session()->get('videos') as $index=>$video)
            <div onclick="changeUrl('{{$video['path']}}')"
                class="px-3 py-1.5 rounded bg-gray-500 text-white flex gap-2 cursor-pointer mt-1">{{$index+1}}. Video
            </div>
            @endforeach
        </div>
        @endif

        {{-- <input id="file-upload" type="file"> --}}
        <div @click="$refs.open.click()" id="drag"
            class=" border-[#D1D5DB] border  mt-2 cursor-pointer  text-[#4B5563] rounded-md  py-1.5 w-full  text-center shadow-[0px_1px_3px_0px_rgba(0,0,0,0.10)]  block">
            Drag & Drop your files or <span class=" underline">Browse</span>
        </div>
        <input type="file" name="" id="file-upload" x-ref="open" accept="video/mp4,video/mkv, video/x-m4v,video/*"
            class="hidden py-1.5 shadow-[0px_1px_3px_0px_rgba(0,0,0,0.10)]">
    </div>
    <p class="text-gray-500 text-sm">Please select a video that is smaller than 500MB in size.</p>
    <div class="fi-fo-field-wrp-error-message text-sm text-danger-600 dark:text-danger-400">
        @error('video') {{ $message }} @enderror</div>
    <div>

        <div style="display: none" class="progress mt-3" style="height: 6px" wire:ignore>
            <div style="background: linear-gradient(to left, #F2709C, #FF9472);box-shadow: 0 3px 3px -5px #F2709C, 0 2px 5px #F2709C;	border-radius: 20px;
        " class="progress-bar py-[6px] progress-bar-striped  progress-bar-animated px-2 text-white" role="progressbar"
                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">75%</div>
        </div>
        <div class="card-footer mt-2" style="display: none" wire:ignore>
            <video id="videoPreview" controls style="width: 100%; height: 70%; display: none"></video>
        </div>
    </div>
    <script>
          var findDurationInterval;
        var r = new Resumable({
            target: '/upload_chunk',
            chunkSize: 1*1024*1024, // 1 MB per chunk, adjust as needed
            query: {_token: '{{ csrf_token() }}'},
            testChunks: false,
            throttleProgressCallbacks: 1,
            maxFileSize:500*1024*1024
            // minFileSize:
        });

        r.assignBrowse(document.getElementById('file-upload'));
        r.assignDrop(document.getElementById('drag'));
        r.on('fileAdded', function(file){

            showProgress();
            // Start uploading once a file is added
            r.upload();
        });


        r.on('fileProgress', function(file) {
            updateProgress(Math.floor(file.progress() * 100));
    // Send the total number of chunks with each chunk
    var totalChunks = file.chunks.length;
    @this.dispatch('totalChunks', { total:totalChunks});
});
r.on('fileSuccess', function (file, response) {
    // trigger when file upload complete
    // r.removeFile(file);
        response = JSON.parse(response)

        // document.getElementById('file-upload').value=response.path + '/' + response.name+'.'+extension[1]
 let filename=response.path + '/' + response.name
let duration;
        if (response.mime_type.includes("video")) {
            $('#videoPreview').attr('src','/storage/' + response.path + '/' + response.name).show();
        }
        const data = {
    filename: filename,
};
         findDurationInterval=setInterval(findDuration.bind(data), 500);

        // $('#videoPreview').on('loadedmetadata', function() {
    // });
        $('.card-footer').show();

        // @this.set('{{ $getStatePath() }}', response.path + '/' + response.name+'.'+extension[1])

    });
        r.on('fileError', function(file, message){
            // Handle errors
            console.log('fail');

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

function changeUrl(url){
let videoPreview=document.getElementById('videoPreview');
videoSrc='/storage/'+url;
videoPreview.src=videoSrc

}


function findDuration(filename){
    duration= $('#videoPreview')[0].duration
    console.log(duration);

    if(duration){
        @this.dispatch('setVideoPath', { videoPath:this.filename,duration:duration});
        clearInterval(findDurationInterval);
    }
}

    </script>
</x-dynamic-component>
