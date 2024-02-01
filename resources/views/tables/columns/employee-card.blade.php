<div class=" card-width">
    <style>
        /* Card Outline Css */

        .fi-ta-record {
            position: relative;
        }

        .fi-ta-record input {
            position: absolute;
            top: 0px;
        }

        .fi-ta-record a {
            padding-left: 10px;
            padding-right: 12px;
        }

        .fi-ta-record {
            border-radius: 4px;
            border: 1px solid #EFEFEF;
        }

        /* Card Section Css  */

        .card {
            background-color: #F5F8FF;
            width: 250px;
        }

        .active-btn {
            border: 1px solid #287D3C;
            background-color: #D3F3DF;
        }

        .employee-image{
            width: 80px;
            height: 80px;
            border-radius: 100%;
        }

        .card-width{
            height: 290px;
        }
    </style>
    <div class=" w-full">
        <div class=" w-full">

            <div @click="open = true" class=" flex justify-end">
                @if(!is_null($getRecord()->employment) && !is_null($getRecord()->employment->employeestatus))
                    @if($getRecord()->employment->employeestatus->name == "Active")
                    <button
                    class=" text-sm text-[#287D3C] bg-[#D3F3DF] border border-[#287D3C] rounded px-1 active-btn">{{$getRecord()->employment->employeestatus->name}}</button>
                    @else
                    <button
                    class=" text-sm text-[#f0613d] bg-[#e04d28] border border-[#db4b38] rounded px-1 active-btn" style="background-color: rgb(190, 106, 106)">{{$getRecord()->employment->employeestatus->name}}</button>
                    @endif
                @endif

            </div>
            <div class=" flex justify-center">
                {{-- @dd($getRecord()->employee->profile_picture_url) --}}
                @if(!is_null($getRecord()->employee) && !is_null($getRecord()->employee->profile_picture_url))
                <img src="{{$getRecord()->employee->profile_picture_url}}" alt="" class=" employee-image">
                @else
                <img src="" alt="" class=" w-[85px] h-[85px]">
                @endif
            </div>
            <div class=" text-center mt-2">
                <div>
                    @if(!is_null($getRecord()->employment) && !is_null($getRecord()->employment->employment_id))
                    <span class=" text-[#6B7280] font-medium">{{$getRecord()->employment->employment_id}}</span>
                    <span class=" text-[#172B4D] font-medium">-
                        {{ $getRecord()->name }}</span>
                </div>
                @endif
                {{-- @if($getRecord()->roles[0]->name) --}}
                {{-- @dd($getRecord()->roles) --}}
                @php
                try {
                $roleName = optional($getRecord()->roles[0])->name ?? 'Default Role';
                } catch (\Exception $e) {
                $roleName = "----";
                }
                @endphp
                <h3 class=" text-sm text-[#6B7280] mt-1">{{$roleName }}</h3>
                {{-- @endif --}}
            </div>
            <div class=" bg-[#F5F8FF] py-2 px-3 mt-5 rounded card">
                <div class=" flex items-center gap-x-3 w-full">
                    <img src="/icon/profile-2.svg" alt="">
                    @if(!is_null($getRecord()->jobinfo))
                    <h4 class=" text-[#09101D]">
                        @if($getRecord()->jobinfo->designation == null)
                        -------
                        @else
                        {{$getRecord()->jobinfo->designation->name}}
                        @endif </h4>
                    </h4>
                    @endif
                </div>
                <div class=" flex items-center gap-x-3 mt-3 w-full">
                    <img src="/icon/message.svg" alt="">
                    <h4 class=" text-[#09101D] truncate">
                        {{$getRecord()->email}}
                    </h4>
                </div>
                <div class=" flex items-center gap-x-3 mt-3 w-full">
                    <img src="/icon/phone.svg" alt="">
                    <h4 class=" text-[#09101D]">+1
                        @if(!is_null($getRecord()->employee) && !is_null($getRecord()->employee->social_security_number)
                        )
                        {{$getRecord()->employee->social_security_number}}
                        @else
                        -------
                        @endif
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <x-filament::modal id="edit-user">
    {{-- Modal content --}}
    hiii
</x-filament::modal>
</div>
