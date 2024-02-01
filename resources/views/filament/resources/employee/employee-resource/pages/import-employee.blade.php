<x-filament-panels::page>
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

    <div>
        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">

           Excel File<!--[if BLOCK]><![endif]--><sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
             <!--[if ENDBLOCK]><![endif]-->
        </span>
        <div @click="$refs.open.click()" id="drag"
        class=" border-[#D1D5DB] border  mt-2 cursor-pointer  text-[#4B5563] rounded-md  py-1.5 w-full  text-center shadow-[0px_1px_3px_0px_rgba(0,0,0,0.10)]  block">
        Drag & Drop your files or <span class=" underline">Browse</span>
    </div>
    <input type="file" name="" id="file-upload" x-ref="open" accept=".xlsx, .xls"
        class="hidden py-1.5 shadow-[0px_1px_3px_0px_rgba(0,0,0,0.10)]">
        <p class="text-gray-500 text-sm">The file must be in xlsx format.</p>

        <div style="display: none" class="progress mt-3" style="height: 6px" wire:ignore>
            <div style="background: linear-gradient(to left, #F2709C, #FF9472);box-shadow: 0 3px 3px -5px #F2709C, 0 2px 5px #F2709C;	border-radius: 20px;
        " class="progress-bar py-[16px] progress-bar-striped  progress-bar-animated px-2 text-white" role="progressbar"
                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">75%</div>
        </div>

    </div>
@if ($collection)

    <div class="bg-blue-500 text-white px-4 rounded py-2" style="">Following fields are required and must be matched:
        <strong>
            @foreach ($options as $key=>$option)

            <span value="{{$key}}">{{$option[1]?$option[0].',':''}} </span>
            @endforeach</strong></div>
    <div class=" flex items-center gap-x-6 ">
        <div>
            <div class="py-2">Select database Field </div>
            @foreach ($collection as $key=>$item)
            <div class=" grid gap-y-6">

                @if ($key==0)
                @foreach ($item as $index=>$column)

                <select class="rounded" wire:model.live.debounce='columnList.{{$index}}' name="" id="{{ $column}}">
                    <option value="">Select option</option>
                    @foreach ($options as $key=>$option)

                    <option value="{{$key}}">{{$option[0]}} {{$option[1]?'*':''}}</option>
                    @endforeach
                </select>


                @endforeach

                @endif
            </div>
            @endforeach
        </div>

        <div>
            <div class="py-2">Excel Field </div>

            @foreach ($collection as $key=>$item)
            <div class=" grid gap-y-6">
                @if ($key==0)

                @foreach ($item as $column)
                <p class=" py-2 border border-black rounded " style="padding-inline:1.5rem">
                    {{$column}}
                </p>
                @endforeach
                @endif

            </div>
            @endforeach
        </div>
    </div>
    <div>
        <div
            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white focus-within:ring-2 dark:bg-white/5 ring-gray-950/10 focus-within:ring-primary-600 dark:ring-white/20 dark:focus-within:ring-primary-500 fi-fo-select">
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

        <button wire:click='save'
            style="{{$requiredOptions==$selectedRequiredOptions?'background:blue':'opacity:0.7' }}"
            class="bg-blue-600 px-2 py-1 mt-4 rounded text-white w-fit"> Import</button>
    </div>
@endif

    {{-- disabled= "{{$requiredOptions==$selectedRequiredOptions?'false':'true' }}" --}}
    @if ($employeeData)
    <div class="overflow-y-auto">
        <table id="importData"
            class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-white/5">

                <tr>
                    <td class="border p-2 ">Skip</td>
                    @foreach (array_keys($employeeData[0]) as $key)
                    <td class="border p-2 font-bold">{{$key}}</td>
                    @endforeach

                </tr>
            </thead>

            @foreach ($employeeData as $index=>$data)
            <tr>
                <td class="border p-2 "><input type="checkbox" class="rounded" name="" wire:model='skipEmployee.{{$index}}' id=""></td>

                @foreach ($data as $value)
                <td class="border p-2 ">{{$value}}</td>
                @endforeach
            </tr>
            @endforeach

        </table>
    </div>
    <button wire:click='create' class="bg-blue-600 px-2 py-1 mt-4 rounded text-white w-fit">
        <span wire:loading.remove>Create</span>
        <div wire:loading wire:target="create">
            <div class="load-wrapp">
                <div class="load-2">
                  <div class="line"></div>
                  <div class="line"></div>
                  <div class="line"></div>
                </div>
              </div>
        </div>
    </button>
    @endif

    <script>
      var r = new Resumable({
          target: '/upload_excel',
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
   let extension=response.mime_type.split("-")
    let filename=response.path + '/' + response.name
    console.log(filename);
    @this.dispatch('setExcelPath', { videoPath:filename});

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
</x-filament-panels::page>
