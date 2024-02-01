<div>
    
    {{-- logo --}}
    <div class=" shadow-md">
        <div class="container mx-auto px-5 py-2">
            <img src="/images/guru hr logo 1.png" alt="" class="max-w-xs" >
        </div>
       
    </div>

    @if (session()->has('message'))
        <div class="text-center rounded-md flex justify-center mt-5  text-xl bg-gray-300 p-4 container mx-auto px-40"
            wire:poll.keep-alive>{{ session('message') }}</div>
    @endif

   
   {{-- <a href="/storage.pdf">click here</a> --}}
   <div class="  container mx-auto px-5 lg:px-40 lg:mt-20 mt-10">
    <h1 class=" text-xl lg:text-3xl font-bold py-3">Offer Letter</h1>
    <embed type="application/pdf" src="/{{$emp_id}}storage.pdf" width="100%" height="700" class="bg-white"/>
   </div>
 
   

    <div class=" container mx-auto lg:px-40 px-5">
        <form wire:submit.prevent="fileUpload()" id="from-upload">
            <div class="mt-20">
                <div class="border border-black relative py-[11px] rounded px-3 w-full w-[40%]">
                    <input type="file" class="  p-2 rounded mt-3" id="fileInput" required />
                    <label
                        class=" bg-[#D1D5D8] absolute py-[10px] top-[1px]  right-[1px]  font-semibold  px-10 cursor-pointer"
                        for="fileInput">Browse</label>
                    <span id="fileNameDisplay">No file chosen</span>
                </div>

                {{-- <label for="" class="block text-xl">Upload your file</label> --}}

                {{-- <input type="file" class=" w-[50%] p-2 rounded mt-3 border border-black" wire:model="files" required> --}}

                @error('files')
                    <span class="text-red-600">{{ $message }}</span>
                @enderror
            </div>
            <div class=" space-x-5 mt-4 mb-10">

                <button class="bg-[#B39800] px-3 py-1 mt-4 rounded text-white w-fit">Upload</button>
                <a href="" class="px-2 py-2 mt-2 rounded border border-gray-300">Cancel</a>

            </div>
        </form>

    </div>
    <script>
        document.getElementById('fileInput').addEventListener('change', function() {
            var fileName = this.value.split('\\').pop(); // Extract file name
            document.getElementById('fileNameDisplay').textContent = fileName; // Update the display
        });
    </script>
    <style>
        #fileInput {
            opacity: 0;
            position: absolute;
            z-index: -1;
        }

        #fileInput+label {
            cursor: pointer;

        }
    </style>

</div>
