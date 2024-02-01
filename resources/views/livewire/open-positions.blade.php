<div>


    <div class=" relative">

        <header class="my-3 container  mx-auto px-5">
            <img src="/images/guru hr logo 1.png" alt="file missing" width="200px">
        </header>

        <div class="border-[#E5E7EB] border shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]"></div>
        <div class="container mx-auto px-5">
            <div class="">

                <div class="">
                    <h1 class="font-bold text-2xl pt-16">Current Openings</h1>
                    <div class=" flex justify-between">
                        <h4 class="font-normal mt-3">Thanks for checking out our position openings. See something that
                            interests
                            you? Apply here.</h4>

                        <!-- <div
                            class="shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] rounded-lg cursor-pointer border border-[#D1D5DB] gap-10 flex px-5 py-2">
                            <h4>Select Location</h4>
                            <select name="" id="" class="outline-none"></select>
                        </div> -->



                        <div
                            class="mt-2 border border-[#D1D5DB] rounded-lg  placeholder:text-[#666666]  bg-white shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                            <select name="" id="" class="text-[#374151] fi-select-input rounded-lg border border-[#D1D5DB] w-[170px] py-2 px-3 bg-transparent text-base transition duration-75 focus:ring-0  border-none bg-white"
                                wire:model='branch' wire:click='company'>
                                <option value="all" >Select Location</option>
                                {{-- @dd($companies) --}}
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    <!-- card section start  -->
                    @if (count($this->Jobs) > 0)
                        <div class="border border-[#D1D5D8] rounded-lg mt-14">
                            @foreach ($Jobs as $Job)
                                <a href="/job-description/{{ $Job->id }}">
                                    <div wire:key="job-{{ $Job->id }}">
                                        <div class="hover:bg-[#F5F5F9] pl-3 py-3 cursor-pointer">
                                            <h1 class="font-medium">{{ $Job->title }}</h1>
                                            <span class="text-sm ">{{ $Job->designation->name }}</span>
                                        </div>
                                    </div>
                                    <div class="border border-[#D1D5D8]"></div>
                                </a>
                            @endforeach

                        </div>
                    @else
                        <div>There are No Jobs</div>
                    @endif
                    <!-- card section end  -->
                </div>
            </div>
        </div>
      @if (session('status'))
        <div x-data="{
            show:true,

        }">

            <div id="successNotification" x-cloak x-show="show" x-transition
                class=" flex items-center border px-5 py-2 absolute gap-x-3 rounded-lg right-10  shadow top-24">
                <x-heroicon-o-check-circle class=" text-green-400 w-8 h-8" />
                <span>Applied Successfully</span>
                <x-heroicon-o-x-mark class=" w-5 h-5 cursor-pointer" @click="show=false" />
            </div>
        </div>
        @endif
    </div>
    <style>
        .bg-white:focus {
            outline: none;
        }
        [x-cloak] { display: none !important; }
    </style>
    <script>
        setTimeout(() => {
document.getElementById('successNotification').style.display = "none"
        }, 5000);
    </script>
</div>

{{-- src="images/guru hr logo 1.png"
src="images/guru hr logo 1.png" --}}
