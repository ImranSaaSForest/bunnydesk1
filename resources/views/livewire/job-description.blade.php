<div>
    <div class="" x-data>

        <header class="my-3  container  mx-auto px-5">
            <img src="/images/guru hr logo 1.png" alt="file missing" width="200px">
        </header>
        <div class="border-[#E5E7EB] border shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]"></div>

        <div class="flex gap-10 container  mx-auto px-5 mt-14">
            <div class="border shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] w-full rounded-lg ">
                <div class="flex items-center gap-4 text-[#666666] px-6 pt-5">
                    <a href="/open-positions"><img src="/images/left-arrow.svg" alt=""
                            class="cursor-pointer"></a>

                    <span class="">Position Openings</span>
                </div>
                <div class=" py-6 px-4">
                    <h1 class="font-medium text-lg">{{ $Job->title }}</h1>
                    <span class="text-base mt-3 block">{{ $Job->designation->name }} - {{ $Job->country }},
                        {{ $Job->city }}</span>
                </div>
                <div class="border-b border-[#D1D5D8] mx-4"></div>
                <h1 class="px-4 py-6 text-base">{{ $Job->description }}</h1>

                <div class="bg-[#F7F7F7] border-t border-[#D1D5D8] px-4">
                    <a href="/job-application/{{ $Job->id }}"><button
                            class="bg-[#B39800] rounded-lg pl-4 pr-4 pt-2 pb-2 my-5 text-white font-medium shadow-[0px_1px_3px_0px_rgba(0,0,0,0.16)] action-button">Apply
                            For This Position</button></a>

                </div>
            </div>

            {{-- employeeType --}}

            <!-- right side card section open  -->
            <div class="border shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] rounded-lg py-3 w-[40%]">
                <div class="border-[#D1D5D8]  p-5 border-b">
                    <h1 class="font-medium  text-lg pb-1">Location</h1>
                    <span lass="block text-base ">{{ $Job->country }}, {{ $Job->city }}</span>
                </div>

                <div class="border-[#D1D5D8] p-5 border-b">
                    <h1 class="font-medium  text-lg pb-1">Position</h1>
                    <span class="block text-base">{{ $Job->title }}</span>
                </div>

                <div class=" p-5">
                    <h1 class="font-medium  text-lg pb-1">Employment Type</h1>
                    <span lass="block text-base ">{{ $emptyp }}</span>
                </div>
            </div>
            <!-- right side card section close  -->
        </div>
    </div>
    <style>
         .action-button
{
    text-decoration: none;
}
.action-button:active
{
    transform: translate(0px,5px);
  -webkit-transform: translate(0px,5px);
}
    </style>
</div>
