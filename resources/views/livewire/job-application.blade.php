<div>
    <div class="" x-data>
        <header class="my-3 container mx-auto px-5">
            <img src="/images/guru hr logo 1.png" alt="" width="200px">
        </header>
        <div class="border-[#E5E7EB] border shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]"></div>

        <div class="container mx-auto px-5">
            <div class="flex gap-14">
                <div class="border shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] py-7 rounded-lg my-10 w-full">
                    <div class="flex gap-4 text-[#666666] px-10 items-center">
                        <a href="/job-description/{{ $this->JobID }}"><img src="/images/left-arrow.svg" alt=""
                                class="cursor-pointer"> </a>
                        <span class=" text-[16px] block">Position Openings</span>
                    </div>
                    <div class=" my-6 mx-7">
                        <h1 class="font-medium text-lg">{{ $Job->title }}</h1>
                        <span class="text-base mt-3 block">{{ $Job->designation->name }} - {{ $Job->country }},
                            {{ $Job->city }}</span>
                    </div>

                    <form wire:submit="submit">
                        <div class="mx-6">
                            <div class="border my-2"></div>

                            <div>
                                <!-- Apply for this position start -->


                                <h1 class="pt-6 pb-10 font-bold">Apply for this position</h1>

                                <div class="grid grid-cols-2 gap-x-20">
                                    <div>
                                        <h3 class="text-[#374151]">First Name<span class="text-[#B4173A]">*</span></h3>
                                        <input type="text" name="" id="" wire:model='fname'
                                            class="mt-2 border-[#D1D5DB] border py-1.5 pl-3 w-full rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                        <div>
                                            @error('fname')
                                                <span class="error text-red-700">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-[#374151]">Last Name<span class="text-[#B4173A]">*</span></h3>
                                        <input type="text" name="" id="" wire:model='lname'
                                            class="mt-2 border border-[#D1D5DB] pl-3 py-1.5 w-full rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                        <div>
                                            @error('lname')
                                                <span class="error text-red-700">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="my-5">
                                        <h3 class="text-[#374151]">Email Address<span class="text-[#B4173A]">*</span>
                                        </h3>
                                        <input type="text" name="" id="" wire:model='email'
                                            class="mt-2 border border-[#D1D5DB] pl-3 py-1.5 w-full rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                        <div>
                                            @error('email')
                                                <span class="error text-red-700">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="my-5">
                                        <h3 class="text-[#374151]">Phone Number<span class="text-[#B4173A]">*</span>
                                        </h3>
                                        <input type="number" name="" id="" wire:model='number'
                                            class="mt-2 border border-[#D1D5DB] pl-3 w-full py-1.5 rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                        @error('number')
                                            <span class="error text-red-700">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div x-data class="relative mt-5 ">
                                        <h5 class="text-[#374151] font-medium">Photo (MaxSize: 1mb)<span
                                                class="text-[#B4173A]">*</span></h5>
                                        <div @click="$refs.open.click()"
                                            class=" border-[#D1D5DB] border  mt-2  text-[#4B5563] rounded-md  py-1.5 w-full  text-center shadow-[0px_1px_3px_0px_rgba(0,0,0,0.10)]  block">
                                            Drag & Drop your files or <span class=" underline">Browse</span>
                                        </div>
                                        <input type="file" id="" x-ref="open" wire:model.live='photo'
                                            class="hidden py-1.5 shadow-[0px_1px_3px_0px_rgba(0,0,0,0.10)]">
                                        @if ($photo)
                                            @php
                                                // if($photo){
                                                try {
                                                    echo ' <img src="' .
                                                        $photo->temporaryUrl() .
                                                        '"
                                            class="w-full h-36 mt-1 rounded-lg object-cover">
                                            <img src="/icon/black-cancel.svg" alt="" wire:click="removeImage"
                                            class=" absolute top-[85px] right-5 cursor-pointer  bg-slate-300 rounded-full ">

                                        ';
                                                } catch (\Throwable $th) {
                                                    echo '  ';
                                                }
                                            @endphp
                                            {{-- @elseif ($image)
                                        <img src="/storage/{{$image}}"
                                            class="w-full h-36 mt-1 rounded-lg border border-[#D1D5DB]"> --}}
                                        @endif
                                        @error('photo')
                                            <span class="error text-red-700">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    @foreach ($jobAddons as $jobAddon)
                                        @switch($jobAddon->job_additional_id)
                                            @case(1)
                                                <div class="my-5" wire:key="ja-{{ $jobAddon->id }}">
                                                    <h3 class="text-[#374151]">Desired Salary (per Annum)
                                                        @if ($this->case1)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>
                                                    <input type="text" name="" id="" wire:model='salary'
                                                        class="mt-2 border border-[#D1D5DB] pl-3 w-full py-1.5 rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    @error('salary')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @break

                                            @case(2)
                                                <div class="my-5" wire:key="ja-{{ $jobAddon->id }}">
                                                    <h3 class="text-[#374151]"> LinkedIn URL
                                                        @if ($this->case3)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>
                                                    <input type="text" name="" id="" wire:model='linkedUrl'
                                                        class="mt-2 border border-[#D1D5DB] pl-3 w-full py-1.5 rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    @error('linkedUrl')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @break

                                            @case(3)
                                                <div class="my-5" wire:key="ja-{{ $jobAddon->id }}">
                                                    <h3 class="text-[#374151]">Referred By (Employee ID)
                                                        @if ($this->case4)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>
                                                    <input type="text" name="" id="" wire:model='reffredBy'
                                                        class="mt-2 border border-[#D1D5DB] pl-3 w-full py-1.5 rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    @error('reffredBy')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @break

                                            @case(4)
                                                <div class="my-5" wire:key="ja-{{ $jobAddon->id }}">
                                                    <h3 class="text-[#374151]">Website, Blog or Portfolio (URL)
                                                        @if ($this->case5)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>
                                                    <input type="text" name="" id="" wire:model='website'
                                                        class="mt-2 border border-[#D1D5DB] pl-3 w-full py-1.5 rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    @error('website')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @break

                                            @case(5)
                                                <div class="mt-5" wire:key="ja-{{ $jobAddon->id }}">
                                                    <h3 class="text-[#374151]">References
                                                        @if ($this->case6)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>

                                                    <div
                                                        class="border border-[#D1D5DB] mt-2 rounded-lg px-3 shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] ">
                                                        <select name="cars" id="cars" wire:model='refrences'
                                                            class=" mr-5  py-1.5 fi-select-input block w-full bg-transparent text-base transition duration-75 focus:ring-0  border-none bg-white ">
                                                            <option value="Website">...</option>
                                                            <option value="Website">Website</option>
                                                            <option value="Social Media">Social Media</option>
                                                            <option value="Newspaper">Newspaper</option>
                                                            <option value="Employee Referral">Employee Referral</option>
                                                        </select>
                                                    </div>
                                                    {{-- <input type="text" name="" id=""
                                            class="mt-2 border border-[#D1D5DB] pl-3 w-full py-1.5 rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                        --}}
                                                    @error('refrences')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @break

                                            @case(6)
                                                <div class=" " wire:key="ja-{{ $jobAddon->id }}">
                                                    <h3 class="text-[#374151]">Resume (MaxSize: 5mb)
                                                        @if ($this->case7)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>
                                                    <div
                                                        class="flex mt-2 justify-between pl-5 py-[8px] relative items-center border border-[#D1D5D8] rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.10)]">
                                                        <input class="file" type="file" name="" id="actual-btn"
                                                            wire:model='resume'
                                                            class=" pt-2   w-full rounded-tl-lg rounded-bl-lg pl-1.5">

                                                        <label id="actual-btn" for="actual-btn"
                                                            class="bg-[#D1D5D8] action-button absolute py-[9px] -right-1  font-medium rounded-br-lg rounded-tr-lg px-10 cursor-pointer">Browse</label>

                                                    </div>
                                                    @error('resume')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @break

                                            @case(7)
                                                <div class="my-5 col-span-2" wire:key="ja-{{ $jobAddon->id }}">
                                                    <h3 class="text-[#374151]">Cover Letter
                                                        @if ($this->case2)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>
                                                    <textarea name="" id="" wire:model='letter'
                                                        class="mt-2 border border-[#D1D5DB] pl-3 w-full py-1.5 rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]"
                                                        cols="30" rows="4"></textarea>
                                                    @error('letter')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @break

                                            @default
                                                <div class="alert alert-dark">

                                                </div>
                                        @endswitch
                                    @endforeach

                                </div>
                            </div> <!-- Apply for this position end -->

                            @foreach ($jobAddons as $jobAddon)
                                @switch($jobAddon->job_additional_id)
                                    @case(8)
                                        <div wire:key="ja-{{ $jobAddon->id }}">
                                            <!-- address section start  -->


                                            <h1 class="font-bold pt-16 pb-10">Address
                                                @if ($this->case8)
                                                    <span class="text-[#B4173A]">*</span>
                                                @endif
                                            </h1>
                                            <div class="grid grid-cols-3 gap-10">

                                                <!-- country -->
                                                <div>
                                                    <div
                                                        class="rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] py-1.5 px-3 border border-[#D1D5DB]">
                                                        <select id="country" class='fi-select-input block w-full bg-transparent text-base transition duration-75 focus:ring-0  border-none bg-white'
                                                            wire:model='country' wire:ignore wire:click='getStates'>
                                                            <option value="" class=" ">Country</option>
                                                            @foreach ($Countries as $code => $countries)
                                                            <option value="{{ $code }}" class=" ">{{ $countries }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('country')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div> <!-- end country row -->


                                                <div>
                                                    <div
                                                        class='rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] py-1.5 px-3 border border-[#D1D5DB]'>
                                                        <select id="region" class='form-control fi-select-input block w-full bg-transparent text-base transition duration-75 focus:ring-0  border-none bg-white  '
                                                            wire:model='state' wire:click='getCities'>
                                                            <option value="">State</option>
                                                            @foreach ($States as $postal => $states)
                                                            <option value="{{ $states }}" class=" ">{{ $states }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('state')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div> <!-- end State row -->

                                                <!-- City -->
                                                <div>
                                                    <div
                                                        class='rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] py-1.5 px-3 border border-[#D1D5DB]'>
                                                        <input id="city" placeholder="City" class='form-control fi-select-input block w-full bg-transparent text-base transition duration-75 placeholder:text-[#666666]  focus:ring-0  border-none bg-white  '
                                                            wire:model='city'>
                                                    </div>
                                                    @error('city')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div><!-- end City row -->

                                                <div>
                                                    <input type="text" placeholder="Postal Code" wire:model='postalCode'
                                                        class="border border-[#D1D5DB] pl-3 py-1.5 rounded-lg placeholder:text-[#666666] w-full shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    @error('postalCode')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <input type="text" placeholder="House/Flat No" wire:model='houseNo'
                                                        class="border border-[#D1D5DB] pl-3 py-1.5  placeholder:text-[#666666] w-full rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    @error('houseNo')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <input type="text" placeholder="Street 1" wire:model='street1'
                                                        class="border border-[#D1D5DB] pl-3 py-1.5  rounded-lg placeholder:text-[#666666] w-full shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    @error('street1')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <input type="text" placeholder="Street 2" wire:model='street2'
                                                        class="border border-[#D1D5DB] pl-3 py-1.5  rounded-lg placeholder:text-[#666666] w-full shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    @error('street2')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>



                                            </div>


                                        </div> <!-- address section end  -->
                                    @break

                                    @case(9)
                                        <div wire:key="ja-{{ $jobAddon->id }}">
                                            <h1 class="font-bold pt-16 pb-10">Education Details </h1>
                                            <div class=" grid grid-cols-2 gap-10">
                                                <div>
                                                    <div>
                                                        <h3 class="">College Name
                                                            @if ($this->case9)
                                                                <span class="text-[#B4173A]">*</span>
                                                            @endif
                                                        </h3>


                                                        <input type="text" name="" id=""
                                                            wire:model='collegeName'
                                                            class="mt-2 border-[#D1D5DB] border pl-1.5 py-1.5 2xl:w-[450px] lg:w-[270px] xl:w-[360px] rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    </div>
                                                    @error('collegeName')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                {{-- h --}}
                                                <div class="" wire:key="ja-{{ $jobAddon->id }}">
                                                    <h3 class="text-[#374151]">Highest Education
                                                        @if ($this->case9)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>
                                                    <input type="text" name="" id=""
                                                        wire:model='highEducation'
                                                        class="mt-2 border border-[#D1D5DB] pl-3 w-full py-1.5 rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    @error('highEducation')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                {{-- h --}}
                                                <div>
                                                    <div>
                                                        <h3 class="">Branch
                                                            @if ($this->case9)
                                                                <span class="text-[#B4173A]">*</span>
                                                            @endif
                                                        </h3>
                                                        <input type="text" name="" id=""
                                                            onkeydown="return /[a-z]/i.test(event.key)" wire:model='branch'
                                                            class="mt-2 border-[#D1D5DB] border pl-1.5 py-1.5 2xl:w-[450px] lg:w-[270px] xl:w-[360px] rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    </div>
                                                    @error('branch')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <div>
                                                        <h3 class="">Batch
                                                            @if ($this->case9)
                                                                <span class="text-[#B4173A]">*</span>
                                                            @endif
                                                        </h3>
                                                        <input type="number" name="" id=""
                                                            wire:model='batch'
                                                            class="mt-2 border-[#D1D5DB] border pl-1.5 py-1.5 2xl:w-[450px] lg:w-[270px] xl:w-[360px] rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    </div>
                                                    @error('batch')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <div>
                                                        <h3 class="">Percentage/CGPA
                                                            @if ($this->case9)
                                                                <span class="text-[#B4173A]">*</span>
                                                            @endif
                                                        </h3>
                                                        <input type="number" name="" id=""
                                                            wire:model='grade'
                                                            class="mt-2 border-[#D1D5DB] border pl-1.5 py-1.5 2xl:w-[450px] lg:w-[270px] xl:w-[360px] rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    </div>
                                                    @error('grade')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @break

                                    @case(10)
                                        <div wire:key="ja-{{ $jobAddon->id }}">
                                            <h1 class="font-bold pt-16 pb-10">Visa Validation</h1>
                                            <div class=" grid grid-cols-2 gap-10">
                                                <div>
                                                    <h3 class="">Type
                                                        @if ($this->case10)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>

                                                    <div
                                                        class="mt-2 border border-[#D1D5DB] py-1.5 placeholder:pl-4 rounded-lg placeholder:text-[#666666] 2xl:w-[450px] lg:w-[270px] xl:w-[360px] px-3 bg-white shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                        <select name="" id="" class=" fi-select-input block w-full bg-transparent text-base transition duration-75 focus:ring-0  border-none bg-white  "
                                                            wire:model='visaType'>
                                                            <option>Entry visa</option>
                                                            <option>Employment Visa</option>
                                                            <option>Project Visa</option>
                                                            <option>Business Visa</option>
                                                        </select>
                                                    </div>
                                                    @error('visaType')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <h3 class="">Visa Number
                                                        @if ($this->case10)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>

                                                    <input type="text" name="" id="" maxlength="12"
                                                        wire:model='visaNumber'
                                                        class="mt-2 border-[#D1D5DB] border pl-1.5 py-1.5 2xl:w-[450px] lg:w-[270px] xl:w-[360px] rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    @error('visaNumber')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <h3 class="">Expiry
                                                        @if ($this->case10)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>
                                                    <input type="date" name="" id="" wire:model='expiry'
                                                        class="mt-2 border-[#D1D5DB] border py-1.5 2xl:w-[450px] lg:w-[270px] xl:w-[360px] rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    @error('expiry')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <h3 class="">Passport Number
                                                        @if ($this->case10)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>
                                                    <input type="text" name="" id=""
                                                        wire:model='passportNumber'
                                                        class="mt-2 border-[#D1D5DB] border py-1.5 2xl:w-[450px] lg:w-[270px] xl:w-[360px] rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    @error('passportNumber')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <h3 class="">National ID Number

                                                        @if ($this->case10)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>
                                                    <input type="text" name="" id=""
                                                        wire:model='nationalNumber'
                                                        class="mt-2 border-[#D1D5DB] border py-1.5 2xl:w-[450px] lg:w-[270px] xl:w-[360px] rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]">
                                                    @error('nationalNumber')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class=" ">
                                                    <h3 class="text-[#374151]">Visa Image Upload (MaxSize: 1mb)
                                                        @if ($this->case10)
                                                            <span class="text-[#B4173A]">*</span>
                                                        @endif
                                                    </h3>
                                                    <div
                                                        class="flex mt-2 justify-between pl-5 py-[8px] relative items-center border border-[#D1D5D8] rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.10)]">
                                                        <input class="file" type="file" name="" id="visaImg"
                                                            wire:model='visaImg'
                                                            class=" pt-2   w-full rounded-tl-lg rounded-bl-lg ">

                                                        <label id="visaImg" for="visaImg"
                                                            class="bg-[#D1D5D8] action-button absolute py-[9px] -right-1  font-medium rounded-br-lg rounded-tr-lg px-10 cursor-pointer">Browse</label>

                                                    </div>
                                                    @error('visaImg')
                                                        <span class="error text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                            </div>
                                        </div>
                                    @break

                                    @default
                                        <div class="alert alert-dark">

                                        </div>
                                @endswitch
                            @endforeach

                            <div class="">
                                <a> <button type="submit"
                                        class="bg-[#B39800] action-button rounded-lg px-4 py-1.5  mt-10 text-white font-medium ">Submit
                                        Application</button></a>

                            </div>
                        </div>
                    </form>
                </div>

                <!-- right side position start  -->
                <div class="w-[40%]">
                    <div class="border shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] rounded-lg my-10 h-fit">
                        <div class="border-[#D1D5D8] p-5 border-b">
                            <h1 class="font-medium  text-lg">Location</h1>
                            <span lass="block text-base">{{ $Job->country }}, {{ $Job->city }}</span>
                        </div>

                        <div class="border-[#D1D5D8] p-5 border-b">
                            <h1 class="font-medium  text-lg">Position</h1>
                            <span class="block text-base">{{ $Job->title }}</span>
                        </div>

                        <div class=" p-5">
                            <h1 class="font-medium  text-lg">Employment Type</h1>
                            <span lass="block text-base">{{ $emptyp }}</span>
                        </div>

                    </div>


                    <div>
                        <a href="/job-description/{{ $Job->id }}"> <button
                                class="font-medium text-center w-full rounded-lg  shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] border-[#D1D5DB]  border py-2">View
                                Job Details</button></a>

                    </div>

                </div>
                <!-- right side position end  -->


            </div>
        </div>
    </div>

    {{-- <style>
        .file::-webkit-file-upload-button {
            display: none;
        }

        .file::file-selector-button {
            display: none;
        }

        .bg-white:focus {
            outline: none;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0;
        }

        .action-button {
            text-decoration: none;
        }

        .action-button:active {
            transform: translate(0px, 5px);
            -webkit-transform: translate(0px, 5px);
        }
    </style>

    <script>
        // const httpsUrl = 'https://battuta.medunes.net/api/country/all/?key=00000000000000000000000000000000&callback=?';
        // const httpUrl = 'http://battuta.medunes.net/api/country/all/?key=00000000000000000000000000000000&callback=?';

        // async function fetchData() {
        //     // const httpsUrl = 'https://battuta.medunes.net';
        //     // const httpUrl = 'http://battuta.medunes.net';

        //     try {
        //         const response = await fetch(httpsUrl);
        //         const data = await response.json();
        //         console.log('Data from HTTPS:', data);
        //     } catch (error) {
        //         console.error('Failed to fetch data from HTTPS, falling back to HTTP:', error);
        //         const response = await fetch(httpUrl);
        //         const data = await response.json();
        //         console.log('Data from HTTP:', data);
        //     }
        // }

        // // Call the function
        // fetchData();

        $(document).ready(function() {
            //-------------------------------SELECT CASCADING-------------------------//
            var selectedCountry = (selectedRegion = selectedCity = "");
            // This is a demo API key for testing purposes. You should rather request your API key (free) from http://battuta.medunes.net/
            var BATTUTA_KEY = "00000000000000000000000000000000";
            // Populate country select box from battuta API
            url =
                "https://geo-battuta.net/api/country/all/?key=" +
                BATTUTA_KEY +
                "&callback=?";

            async function fetchData() {
                // const httpsUrl = 'https://battuta.medunes.net';
                // const httpUrl = 'http://battuta.medunes.net';

                try {
                    const response = await fetch(url);
                    const data = await response.json();
                    console.log('Data from HTTPS:', data);
                } catch (error) {
                    console.error('Failed to fetch data from HTTPS, falling back to HTTP:', error);
                    const response = await fetch(url);
                    const data = await response.json();
                    console.log('Data from HTTP:', data);
                }
            }

            // EXTRACT JSON DATA.

            $.getJSON(url, function(data) {
                console.log(data);
                $.each(data, function(index, value) {
                    // APPEND OR INSERT DATA TO SELECT ELEMENT.
                    $("#country").append(
                        '<option value="' + value.code + '">' + value.name + "</option>"
                    );
                });
            });

            // Country selected --> update region list .
            $("#country").change(function() {
                selectedCountry = this.options[this.selectedIndex].text;
                countryCode = $("#country").val();
                // Populate country select box from battuta API
                url =
                    "https://geo-battuta.net/api/region/" +
                    countryCode +
                    "/all/?key=" +
                    BATTUTA_KEY +
                    "&callback=?";
                    $.getJSON(url, function(data) {
                        $("#region option").remove();
                        $('#region').append('<option value="">Please select your region</option>');
                        $.each(data, function(index, value) {
                            // APPEND OR INSERT DATA TO SELECT ELEMENT.
                            $("#region").append(
                                '<option value="' + value.region + '">' + value.region +
                                "</option>"
                            );
                        });
                    });
            });
            // Region selected --> updated city list
            $("#region").on("change", function() {
                selectedRegion = this.options[this.selectedIndex].text;
                // Populate country select box from battuta API
                countryCode = $("#country").val();
                region = $("#region").val();
                url =
                    "https://geo-battuta.net/api/city/" +
                    countryCode +
                    "/search/?region=" +
                    region +
                    "&key=" +
                    BATTUTA_KEY +
                    "&callback=?";
                $.getJSON(url, function(data) {
                    console.log(data);
                    $("#city option").remove();
                    $('#city').append('<option value="">Please select your city</option>');
                    $.each(data, function(index, value) {
                        // APPEND OR INSERT DATA TO SELECT ELEMENT.
                        $("#city").append(
                            '<option value="' + value.city + '">' + value.city +
                            "</option>"
                        );
                    });
                });
            });
            // city selected --> update location string
            $("#city").on("change", function() {
                selectedCity = this.options[this.selectedIndex].text;
                $("#location").html(
                    "Locatation: Country: " +
                    selectedCountry +
                    ", Region: " +
                    selectedRegion +
                    ", City: " +
                    selectedCity
                );
            });
        });
    </script> --}}
</div>
