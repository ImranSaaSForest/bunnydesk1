<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <style>
        .fi-modal-content {
            padding: 0px;
        }

        .arrow-section {
            padding: 20px 26px 20px 26px;
        }

        .contact {
            display: flex;
            flex-direction: column;
            row-gap: 20px;
        }

        .information{
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            width: 400px;
        }

        .contact h3 {
            color: #09101D;
        }

        .contact h4 {
            color: #5E5E5E;
        }

        .v{
            height: 100px;
        }

        
    </style>
    <div>
        {{-- {{ $getState() }} --}}
        {{-- @dd($getRecord()->currentAddress) --}}
        {{-- Arrow Section start  --}}
        {{-- <div class=" flex items-center gap-x-4 border-b border-[#D1D5D8] arrow-section">
            <div class=" border border-[#091E4224] rounded w-fit cursor-pointer">
                <img src="/icon/left-arrow.svg" alt="">
            </div>
            <div class=" border border-[#091E4224] rounded w-fit cursor-pointer">
                <img src="/icon/right-arrow.svg" alt="">
            </div>
        </div> --}}
        {{-- Arrow Section end  --}}
        {{-- @dd($getRecord()->employee->profile_picture_url) --}}
        <div class=" border-b border-[#EFEFEF] px-6 py-5 flex items-center gap-x-2.5">
            <img src="{{$getRecord()->employee->profile_picture_url}}" alt="" class=" w-20 h-20 rounded-full">
            <div>
                <h1 class=" text-[#09101D] font-medium text-xl">{{$getRecord()->name}}</h1>
                <h1 class=" text-[#6B7280] font-medium mt-1">
                    @if($getRecord()->jobinfo->designation == null)
                    -------
                    @else
                    {{$getRecord()->jobinfo->designation->name}}
                    @endif </h4>
                </h1>
            </div>
        </div>

        {{-- Contact Information Section Start  --}}
        <div class=" border-b border-[#EFEFEF] px-6 py-5">
            <h1 class=" font-medium text-lg">Contact Information</h1>
            <div class=" information mt-5">
                <div class=" contact">
                    <h3 class=" text-sm text-[#09101D]">Phone Number</h3>
                    {{-- <h3 class=" text-sm text-[#09101D]">Emergency Number</h3> --}}
                    <h3 class=" text-sm text-[#09101D]">Email Address</h3>
                </div>
                <div class=" contact">
                    <h4 class=" text-sm text-[#5E5E5E]">+{{$getRecord()->employee->social_security_number}}</h4>
                    {{-- <h4 class=" text-sm text-[#5E5E5E]">+91 9080983322</h4> --}}
                    <h4 class=" text-sm text-[#5E5E5E]">{{$getRecord()->email}}</h4>
                </div>
            </div>
        </div>
        {{-- Contact Information Section end  --}}

        {{-- Work Information Section start  --}}
        <div class=" border-b border-[#EFEFEF] px-6 py-5">
            <h1 class=" font-medium text-lg">Work Information</h1>
            <div class=" mt-5 information">
                <div class=" contact">
                    <h3 class=" text-sm text-[#09101D] pt-10">Employee ID</h3>
                    <h3 class=" text-sm text-[#09101D]">Designation</h3>
                </div>
                <div class=" contact">
                    <h4 class=" text-sm text-[#5E5E5E]">
                        @if ($getRecord()->employment->employment_id)
                        {{$getRecord()->employment->employment_id}}
                        @else
                        ------
                        @endif
                    </h4>
                    <h4 class=" text-sm text-[#5E5E5E]">
                        @if($getRecord()->jobinfo->designation == null)
                        -------
                        @else
                        {{$getRecord()->jobinfo->designation->name}}
                        @endif </h4>
                    </h4>
                </div>
            </div>
        </div>
        {{-- Work Information Section end  --}}

        {{-- Personal Information Section start  --}}
        <div class=" px-6 py-5">
            <h1 class=" font-medium text-lg">Personal Information</h1>
            <div class=" information mt-5">
                <div class=" contact">
                    <h3 class=" text-sm text-[#09101D]">Full Name</h3>
                    <h3 class=" text-sm text-[#09101D]">Gender</h3>
                    <h3 class=" text-sm text-[#09101D]">Date Of Birth</h3>
                    <h3 class=" text-sm text-[#09101D]">Street</h3>
                    <h3 class=" text-sm text-[#09101D]">City </h3>
                    <h3 class=" text-sm text-[#09101D]">State</h3>
                </div>
                <div class=" contact">
                    <h4 class=" text-sm text-[#5E5E5E]"> {{$getRecord()->name}} {{$getRecord()->last_name}}</h4>
                    <h4 class=" text-sm text-[#5E5E5E]">{{$getRecord()->employee->gender->name}}</h4>
                    <h4 class=" text-sm text-[#5E5E5E]">
                        @if($getRecord()->employee->date_of_birth)
                        {{$getRecord()->employee->date_of_birth}}
                        @else
                          -------
                        @endif
                    </h4>
                    <h4 class=" text-sm text-[#5E5E5E]">
                        @if(!is_null($getRecord()->currentAddress))
                        {{$getRecord()->currentAddress->street}}
                        @else
                        -------
                        @endif

                    </h4>
                    <h4 class=" text-sm text-[#5E5E5E]">
                        @if(!is_null($getRecord()->currentAddress))
                        {{$getRecord()->currentAddress->city}}
                        @else
                        -------
                        @endif
                    </h4>
                    <h4 class=" text-sm text-[#5E5E5E]">
                        @if(!is_null($getRecord()->currentAddress))
                        {{$getRecord()->currentAddress->state}}
                        @else
                        -------
                        @endif
                    </h4>
                </div>
            </div>
        </div>
        {{-- Personal Information Section end  --}}
    </div>
</x-dynamic-component>
