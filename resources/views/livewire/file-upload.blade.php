<div>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>

    <body>
        <div class="my-3 container mx-auto px-5">
            <img src="/images/guru hr logo 1.png" alt="" class="max-w-xs">
        </div>
        @if (session()->has('message'))
            <div class="text-center rounded-md flex justify-center  text-xl bg-gray-300 p-4 container mx-auto px-40"
                wire:poll.keep-alive>{{ session('message') }}</div>
        @endif
        <div class=" container mx-auto px-20">
            <form wire:submit.prevent="fileUpload()" id="from-upload">
                <div class="mt-20">
                    <div class="border border-black relative py-[11px] rounded px-3 w-[40%]">
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
                <div class=" space-x-5 mt-4">

                    <button class="bg-blue-600 px-3 py-1 mt-4 rounded text-white w-fit">Upload</button>
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
    </body>

    </html>
</div>
