<x-filament-panels::page>
    {{-- Profile Section Start  --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <style>
        .onboarding-modal .fi-modal-content {
            padding: 0px;
        }
        .fi-pagination-overview{
            display: inline;
        }
        .fi-ta-ctn{
            /* border-top: 1px solid #E5E7EB; */
            border-radius: 0%;
            --tw-ring-color: none;
        }
        .fi-icon-btn{
            display: none;
        }
        /* .fi-btn{
            background-color: green;
        } */
        .table{
            margin-top: 30px;
            width: 100%;
        }
        .v{
            margin-top: 150px;
        }
    </style>
    <div class=" border border-[#D1D5D8] rounded bg-white">
        <div class=" flex items-end gap-x-16 p-6">
            <div class=" flex items-center gap-x-2.5">
                <div class=" relative">
                    {{-- @dd($this->getRecord->employee->profile_picture_url) --}}
                    <img src="{{$this->getRecord->employee->profile_picture_url}}" alt="" class=" rounded-full w-20 h-20">

                    <label for="file-input" class=" bg-white flex justify-center items-center w-8 h-8 rounded-full absolute bottom-0 right-0 cursor-pointer">
                        <label for="file-input" class=" cursor-pointer">
                            <img id="previewImg" src="/icon/camera.svg" style="" />
                        </label>
                       <input wire:model.live="image" id="file-input" type="file"  style="display: none;" />
                    </label>
                </div>
                <div>
                    <h1 class=" text-[#09101D] font-medium text-xl">{{$this->getRecord->name}}</h1>
                    <h1 class=" text-[#6B7280] font-medium mt-1">
                        @php
                        try {
                        $roleName = optional($this->getRecord->roles[0])->name ?? '----';
                        } catch (\Exception $e) {
                        $roleName = "----";
                        }
                        @endphp
                        {{$roleName}}
                    </h1>
                </div>
            </div>
            <div class=" flex items-center gap-x-3 pb-3">
                <img src="/icon/birthday.svg" alt="">
                {{-- @dd($this->getRecord->employee->profile_picture_url) --}}
                <h3 class=" text-[#6B7280]">
                    @if($this->getRecord->employee->date_of_birth)
                      {{$this->getRecord->employee->date_of_birth}}
                    @else
                        -------
                    @endif
                </h3>
            </div>
            <div class=" flex items-center gap-x-3 pb-3">
                <img src="/icon/roll-no.svg" alt="">
                <h3 class=" text-[#6B7280]">{{$this->getRecord->employment->employment_id}}</h3>
            </div>
            <div class=" flex items-center gap-x-3 pb-3">
                <img src="/icon/bag.svg" alt="">
                <h3 class=" text-[#6B7280]">
                    @php
                 $hiredDate = \Carbon\Carbon::parse($this->getRecord->employment->hired_on);
                 $currentDate = \Carbon\Carbon::now();
                 $interval = $currentDate->diff($hiredDate);
                 $formattedDuration = $interval->format('%y year %m months');
                 @endphp

               {{ $formattedDuration }}
                </h3>
            </div>
        </div>
        {{-- {{$this->getRecord}} --}}
        {{-- Department Section start  --}}
        <div class=" border-t border-[#D1D5D8] flex items-center gap-x-16 px-6 py-5">
            <div>
                <h3 class=" font-medium">Designation</h3>
                {{-- @dd($this->getRecord->jobinfo->designation->name) --}}
                <h3 class=" text-[#6B7280] mt-1">
                    @php
                    try {
                        $roleName = optional($this->getRecord->jobinfo->designation)->name ?? '-----';
                        } catch (\Exception $e) {
                        $roleName = "----";
                        }
                    @endphp
                    {{$roleName}}</h3>
            </div>
            <div>
                <h3 class=" font-medium">Phone number</h3>
                <h3 class=" text-[#6B7280] mt-1">
                    @if(!is_null($this->getRecord->employee) && !is_null($this->getRecord->employee->social_security_number)
                    )
                    {{$this->getRecord->employee->social_security_number}}
                    @else
                    -------
                    @endif
                </h3>
            </div>
            <div>
                <h3 class=" font-medium">Employee type</h3>
                <h3 class=" text-[#6B7280] mt-1">
                    @if(!is_null($this->getRecord->employment->employeetype))
                        @if(!is_null($this->getRecord->employment->employee_type_id))
                          {{$this->getRecord->employment->employeetype->name}}
                        @else
                            -------
                        @endif
                    @else
                        -------
                    @endif
                </h3>
            </div>
            <div>
                <h3 class=" font-medium">Email</h3>
                <h3 class=" text-[#6B7280] mt-1">{{$this->getRecord->email}}</h3>
            </div>
            <div>
                <h3 class=" font-medium">Address</h3>
                <h3 class=" text-[#6B7280] mt-1">
                    @if(!is_null($this->getRecord->currentAddress)!= 0)
                        {{$this->getRecord->currentAddress->street}},{{$this->getRecord->currentAddress->city}}
                    @else
                        --------
                    @endif
                </h3>
            </div>
        </div>
        {{-- Department Section end  --}}
    </div>
    {{-- Profile Section end  --}}

    {{-- Personal Information Section start  --}}
    <div class=" flex mt-5" x-data="{
        val: 1,
    }">
        {{-- List Section Start  --}}
        <div class=" w-1/3" x-data="{
            value: 1,
            active: 'text-[#104FFF] font-medium border-l-[4px] border-[#104FFF] pl-4 py-3 cursor-pointer',
            inactive: 'text-[#6B7280] border-l border-[#D1D5D8] pl-4 flex flex-col py-3 cursor-pointer w-full'
        }">
            <ul class="">
                <li @click="val = 1" :class="val == '1' ? active : inactive" wire:click="SelectTap(1)">Personal Information</li>
                <li @click="val = 2" :class="val == '2' ? active : inactive" wire:click="SelectTap(2)">Job Info</li>
                <li @click="val = 3" :class="val == '3' ? active : inactive" wire:click="SelectTap(3)">Employment Info</li>
                <li @click="val = 4" :class="val == '4' ? active : inactive" wire:click="SelectTap(4)">Compensation</li>
                <li @click="val = 5" :class="val == '5' ? active : inactive" wire:click="SelectTap(5)">Bank Info</li>
                <li @click="val = 6" :class="val == '6' ? active : inactive" wire:click="SelectTap(6)">Education</li>
                <li @click="val = 7" :class="val == '7' ? active : inactive" wire:click="SelectTap(7)">Assets</li>
                <li @click="val = 8" :class="val == '8' ? active : inactive" wire:click="SelectTap(8)">Onboarding</li>
                <li @click="val = 9" :class="val == '9' ? active : inactive" wire:click="SelectTap(9)">Offboarding</li>
            </ul>
        </div>
        {{-- List Section end  --}}

        {{-- Form Section Start  --}}
        <div class=" w-full bg-white">
            <div class=" border border-[#D1D5D8] rounded w-full">
                <div class="">
                    {{-- Personal Information Form Section start  --}}
                    <form wire:submit="create" x-show="val == 1" class=" pb-6">
                        <div class=" flex justify-between items-center mx-3 my-3 px-3 py-3 bg-[#FAFBFB]">
                            <h1 class=" text-xl text-[#09101D] font-medium">Personal Information</h1>
                            <div class=" flex items-end cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M21.3113 6.87846L17.1216 2.68971C16.9823 2.55038 16.8169 2.43986 16.6349 2.36446C16.4529 2.28905 16.2578 2.25024 16.0608 2.25024C15.8638 2.25024 15.6687 2.28905 15.4867 2.36446C15.3047 2.43986 15.1393 2.55038 15 2.68971L3.43969 14.25C3.2998 14.3888 3.18889 14.554 3.11341 14.736C3.03792 14.9181 2.99938 15.1133 3.00001 15.3103V19.5C3.00001 19.8978 3.15804 20.2794 3.43935 20.5607C3.72065 20.842 4.10218 21 4.50001 21H20.25C20.4489 21 20.6397 20.921 20.7803 20.7803C20.921 20.6397 21 20.4489 21 20.25C21 20.0511 20.921 19.8603 20.7803 19.7197C20.6397 19.579 20.4489 19.5 20.25 19.5H10.8113L21.3113 9.00002C21.4506 8.86073 21.5611 8.69535 21.6365 8.51334C21.7119 8.33133 21.7507 8.13625 21.7507 7.93924C21.7507 7.74222 21.7119 7.54714 21.6365 7.36513C21.5611 7.18312 21.4506 7.01775 21.3113 6.87846ZM8.68969 19.5H4.50001V15.3103L12.75 7.06033L16.9397 11.25L8.68969 19.5ZM18 10.1897L13.8113 6.00002L16.0613 3.75002L20.25 7.93971L18 10.1897Z"
                                        fill="#5E5E5E" />
                                </svg>
                                <h3 class=" text-[#5E5E5E]" wire:click="Edit(1)">Edit</h3>
                            </div>
                        </div>
                        {{-- h-[280px] overflow-y-auto --}}
                        <div class=" px-6">
                            {{-- {{ $this->userform }} --}}
                            {{ $this->form }}
                        </div>
                    </form>
                    {{-- Personal Information Section end  --}}


                    {{-- Job Info Form Section start  --}}
                    <form wire:submit="addressCreate" x-show="val == 2" class=" pb-6">
                        <div class=" flex justify-between items-center mx-3 my-3 px-3 py-3 bg-[#FAFBFB]">
                            <h1 class=" text-xl text-[#09101D] font-medium">Job Info</h1>
                            <div class=" flex items-end cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M21.3113 6.87846L17.1216 2.68971C16.9823 2.55038 16.8169 2.43986 16.6349 2.36446C16.4529 2.28905 16.2578 2.25024 16.0608 2.25024C15.8638 2.25024 15.6687 2.28905 15.4867 2.36446C15.3047 2.43986 15.1393 2.55038 15 2.68971L3.43969 14.25C3.2998 14.3888 3.18889 14.554 3.11341 14.736C3.03792 14.9181 2.99938 15.1133 3.00001 15.3103V19.5C3.00001 19.8978 3.15804 20.2794 3.43935 20.5607C3.72065 20.842 4.10218 21 4.50001 21H20.25C20.4489 21 20.6397 20.921 20.7803 20.7803C20.921 20.6397 21 20.4489 21 20.25C21 20.0511 20.921 19.8603 20.7803 19.7197C20.6397 19.579 20.4489 19.5 20.25 19.5H10.8113L21.3113 9.00002C21.4506 8.86073 21.5611 8.69535 21.6365 8.51334C21.7119 8.33133 21.7507 8.13625 21.7507 7.93924C21.7507 7.74222 21.7119 7.54714 21.6365 7.36513C21.5611 7.18312 21.4506 7.01775 21.3113 6.87846ZM8.68969 19.5H4.50001V15.3103L12.75 7.06033L16.9397 11.25L8.68969 19.5ZM18 10.1897L13.8113 6.00002L16.0613 3.75002L20.25 7.93971L18 10.1897Z"
                                        fill="#5E5E5E" />
                                </svg>
                                <h3 class=" text-[#5E5E5E]" wire:click="Edit(2)">Edit</h3>
                            </div>
                        </div>
                        <div class=" px-6">
                                {{ $this->jobForm }}
                                {{$this->roles}}
                        </div>
                    </form>
                    {{-- Job Info Form Section end  --}}

                    {{-- Employment Info Form Section Start  --}}
                    <form wire:submit="addressCreate" x-show="val == 3" class=" pb-6">
                        <div class=" flex justify-between items-center mx-3 my-3 px-3 py-3 bg-[#FAFBFB]">
                            <h1 class=" text-xl text-[#09101D] font-medium">Employment Info</h1>
                            <div class=" flex items-end cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M21.3113 6.87846L17.1216 2.68971C16.9823 2.55038 16.8169 2.43986 16.6349 2.36446C16.4529 2.28905 16.2578 2.25024 16.0608 2.25024C15.8638 2.25024 15.6687 2.28905 15.4867 2.36446C15.3047 2.43986 15.1393 2.55038 15 2.68971L3.43969 14.25C3.2998 14.3888 3.18889 14.554 3.11341 14.736C3.03792 14.9181 2.99938 15.1133 3.00001 15.3103V19.5C3.00001 19.8978 3.15804 20.2794 3.43935 20.5607C3.72065 20.842 4.10218 21 4.50001 21H20.25C20.4489 21 20.6397 20.921 20.7803 20.7803C20.921 20.6397 21 20.4489 21 20.25C21 20.0511 20.921 19.8603 20.7803 19.7197C20.6397 19.579 20.4489 19.5 20.25 19.5H10.8113L21.3113 9.00002C21.4506 8.86073 21.5611 8.69535 21.6365 8.51334C21.7119 8.33133 21.7507 8.13625 21.7507 7.93924C21.7507 7.74222 21.7119 7.54714 21.6365 7.36513C21.5611 7.18312 21.4506 7.01775 21.3113 6.87846ZM8.68969 19.5H4.50001V15.3103L12.75 7.06033L16.9397 11.25L8.68969 19.5ZM18 10.1897L13.8113 6.00002L16.0613 3.75002L20.25 7.93971L18 10.1897Z"
                                        fill="#5E5E5E" />
                                </svg>
                                <h3 class=" text-[#5E5E5E]" wire:click="Edit(3)">Edit</h3>
                            </div>
                        </div>
                        <div class=" px-6">
                            {{ $this->employmentForm }}
                        </div>
                    </form>
                    {{-- Employment Info Form Section end  --}}

                    {{-- Compensation Form Section Start  --}}
                    <form wire:submit="addressCreate" x-show="val == 4" class=" pb-6">
                        <div class=" flex justify-between items-center mx-3 my-3 px-3 py-3 bg-[#FAFBFB]">
                            <h1 class=" text-xl text-[#09101D] font-medium">Compensation</h1>
                            <div class=" flex items-end cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M21.3113 6.87846L17.1216 2.68971C16.9823 2.55038 16.8169 2.43986 16.6349 2.36446C16.4529 2.28905 16.2578 2.25024 16.0608 2.25024C15.8638 2.25024 15.6687 2.28905 15.4867 2.36446C15.3047 2.43986 15.1393 2.55038 15 2.68971L3.43969 14.25C3.2998 14.3888 3.18889 14.554 3.11341 14.736C3.03792 14.9181 2.99938 15.1133 3.00001 15.3103V19.5C3.00001 19.8978 3.15804 20.2794 3.43935 20.5607C3.72065 20.842 4.10218 21 4.50001 21H20.25C20.4489 21 20.6397 20.921 20.7803 20.7803C20.921 20.6397 21 20.4489 21 20.25C21 20.0511 20.921 19.8603 20.7803 19.7197C20.6397 19.579 20.4489 19.5 20.25 19.5H10.8113L21.3113 9.00002C21.4506 8.86073 21.5611 8.69535 21.6365 8.51334C21.7119 8.33133 21.7507 8.13625 21.7507 7.93924C21.7507 7.74222 21.7119 7.54714 21.6365 7.36513C21.5611 7.18312 21.4506 7.01775 21.3113 6.87846ZM8.68969 19.5H4.50001V15.3103L12.75 7.06033L16.9397 11.25L8.68969 19.5ZM18 10.1897L13.8113 6.00002L16.0613 3.75002L20.25 7.93971L18 10.1897Z"
                                        fill="#5E5E5E" />
                                </svg>
                                <h3 class=" text-[#5E5E5E]" wire:click="Edit(4)">Edit</h3>
                            </div>
                        </div>
                        <div class=" px-6">
                            {{ $this->compensationForm }}
                        </div>
                    </form>
                    {{-- Compensation Form Section end  --}}

                    {{-- Bank Form Section Start  --}}
                    <form wire:submit="addressCreate" x-show="val == 5" class=" pb-6">
                        <div class=" flex justify-between items-center mx-3 my-3 px-3 py-3 bg-[#FAFBFB]">
                            <h1 class=" text-xl text-[#09101D] font-medium">Bank Info</h1>
                            <div class=" flex items-end cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M21.3113 6.87846L17.1216 2.68971C16.9823 2.55038 16.8169 2.43986 16.6349 2.36446C16.4529 2.28905 16.2578 2.25024 16.0608 2.25024C15.8638 2.25024 15.6687 2.28905 15.4867 2.36446C15.3047 2.43986 15.1393 2.55038 15 2.68971L3.43969 14.25C3.2998 14.3888 3.18889 14.554 3.11341 14.736C3.03792 14.9181 2.99938 15.1133 3.00001 15.3103V19.5C3.00001 19.8978 3.15804 20.2794 3.43935 20.5607C3.72065 20.842 4.10218 21 4.50001 21H20.25C20.4489 21 20.6397 20.921 20.7803 20.7803C20.921 20.6397 21 20.4489 21 20.25C21 20.0511 20.921 19.8603 20.7803 19.7197C20.6397 19.579 20.4489 19.5 20.25 19.5H10.8113L21.3113 9.00002C21.4506 8.86073 21.5611 8.69535 21.6365 8.51334C21.7119 8.33133 21.7507 8.13625 21.7507 7.93924C21.7507 7.74222 21.7119 7.54714 21.6365 7.36513C21.5611 7.18312 21.4506 7.01775 21.3113 6.87846ZM8.68969 19.5H4.50001V15.3103L12.75 7.06033L16.9397 11.25L8.68969 19.5ZM18 10.1897L13.8113 6.00002L16.0613 3.75002L20.25 7.93971L18 10.1897Z"
                                        fill="#5E5E5E" />
                                </svg>
                                <h3 class=" text-[#5E5E5E]" wire:click="Edit(5)">Edit</h3>
                            </div>
                        </div>
                        <div class=" px-6">
                            {{ $this->bankForm }}
                        </div>
                    </form>
                    {{-- Bank Form Section end  --}}

                    {{-- Education Form Section start  --}}
                    <form wire:submit="addressCreate" x-show="val == 6" class=" pb-6">
                        <div class=" flex justify-between items-center mx-3 my-3 px-3 py-3 bg-[#FAFBFB]">
                            <h1 class=" text-xl text-[#09101D] font-medium">Education</h1>
                            <div class=" flex items-end cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M21.3113 6.87846L17.1216 2.68971C16.9823 2.55038 16.8169 2.43986 16.6349 2.36446C16.4529 2.28905 16.2578 2.25024 16.0608 2.25024C15.8638 2.25024 15.6687 2.28905 15.4867 2.36446C15.3047 2.43986 15.1393 2.55038 15 2.68971L3.43969 14.25C3.2998 14.3888 3.18889 14.554 3.11341 14.736C3.03792 14.9181 2.99938 15.1133 3.00001 15.3103V19.5C3.00001 19.8978 3.15804 20.2794 3.43935 20.5607C3.72065 20.842 4.10218 21 4.50001 21H20.25C20.4489 21 20.6397 20.921 20.7803 20.7803C20.921 20.6397 21 20.4489 21 20.25C21 20.0511 20.921 19.8603 20.7803 19.7197C20.6397 19.579 20.4489 19.5 20.25 19.5H10.8113L21.3113 9.00002C21.4506 8.86073 21.5611 8.69535 21.6365 8.51334C21.7119 8.33133 21.7507 8.13625 21.7507 7.93924C21.7507 7.74222 21.7119 7.54714 21.6365 7.36513C21.5611 7.18312 21.4506 7.01775 21.3113 6.87846ZM8.68969 19.5H4.50001V15.3103L12.75 7.06033L16.9397 11.25L8.68969 19.5ZM18 10.1897L13.8113 6.00002L16.0613 3.75002L20.25 7.93971L18 10.1897Z"
                                        fill="#5E5E5E" />
                                </svg>
                                <h3 class=" text-[#5E5E5E]" wire:click="Edit(6)">Edit</h3>
                            </div>
                        </div>
                        <div class=" px-6">
                            {{ $this->educationForm }}
                        </div>
                    </form>
                    {{-- Education Form Section end  --}}

                    {{-- Assets Table Section start  --}}
                    <div x-show="val == 7">
                        <div class="">
                            <livewire:boarding-table modelName='asset' user_id="{{$auth_user}}"/>
                        </div>
                    </div>
                    {{-- Assets Table Section end  --}}

                    {{-- Onboarding Table Section start  --}}
                    <div x-show="val == 8">
                        <div class="">
                            <livewire:boarding-table modelName='onboarding' user_id="{{$auth_user}}"/>
                            <livewire:boarding-table modelName='on_onboarding' user_id="{{$auth_user}}"/>

                        </div>
                    </div>
                    {{-- Onboarding Table Section end  --}}

                    {{-- Offboarding Table Section start  --}}
                    <div x-show="val == 9">
                        <div>
                            <livewire:boarding-table modelName='offboarding' user_id="{{$auth_user}}"/>
                        </div>
                            <div class=" v">
                                <livewire:boarding-table modelName='on_ofboarding' user_id="{{$auth_user}}"/>
                            </div>

                    </div>
                    {{-- Offboarding Table Section end  --}}
                </div>
            </div>

            {{-- Button Section start  --}}
            @if($this->SelectTabValue==7 || $this->SelectTabValue==8 || $this->SelectTabValue==9)
            @else
            @if( !empty($this->edit_val) && !empty($this->SelectTabValue))
             @if($this->edit_val == $this->SelectTabValue)
            <div class=" flex items-center gap-x-2 mt-6">
                <button class=" text-white font-bold bg-[#104FFF] px-[26px] py-2 rounded-lg" wire:click="Create">Save</button>
                <button class=" border border-[#D1D5D8] bg-white px-[22px] py-2 rounded-lg" wire:click="Cancel">Cancel</button>
            </div>
            @endif
            @endif
            @endif
            {{-- Button Section end  --}}
        </div>
        {{-- Form Section end  --}}

        {{-- Onboarding Slide Over  --}}
        {{-- <div class=" onboarding-modal">
            <x-filament::modal id="onboarding" slide-over width="2xl">
                <div>
                    <h1 class=" border-b border-[#EFEFEF] font-medium p-5">Assign Onboard List</h1>
                    <div class=" px-5 pt-6">
                        {{ $this->onboardingListForm }}
                    </div>
                    <div class=" flex items-center gap-x-2.5 fixed bottom-5 px-5">
                        <button class=" text-white font-bold bg-[#104FFF] px-[26px] py-2 rounded-lg">Assign</button>
                        <button class=" text-[#3A3A3A] border border-[#D1D5D8] px-[26px] py-2 rounded-lg">Assign & Assign Another</button>
                        <button class=" text-[#3A3A3A] border border-[#D1D5D8] px-[26px] py-2 rounded-lg">Cancel</button>
                    </div>
                </div>
            </x-filament::modal>
        </div> --}}

        {{-- Offboarding Slide Over  --}}
        {{-- <div class=" onboarding-modal">
            <x-filament::modal id="offboarding" slide-over width="2xl">
                <div>
                    <h1 class=" border-b border-[#EFEFEF] font-medium p-5">Assign Offboard List</h1>
                    <div class=" px-5 pt-6">
                        {{ $this->offboardingListForm }}
                    </div>
                    <div class=" flex items-center gap-x-2.5 fixed bottom-5 px-5">
                        <button class=" text-white font-bold bg-[#104FFF] px-[26px] py-2 rounded-lg">Assign</button>
                        <button class=" text-[#3A3A3A] border border-[#D1D5D8] px-[26px] py-2 rounded-lg">Assign & Assign Another</button>
                        <button class=" text-[#3A3A3A] border border-[#D1D5D8] px-[26px] py-2 rounded-lg">Cancel</button>
                    </div>
                </div>
            </x-filament::modal>

            {{-- Asset Slide Over  --}}
            {{-- <x-filament::modal id="asset" slide-over width="2xl">
                <div>
                    <h1 class=" border-b border-[#EFEFEF] font-medium p-5">Assign Offboard List</h1>
                    <div class=" px-5 pt-6">
                        {{ $this->offboardingListForm }}
                    </div>
                    <div class=" flex items-center gap-x-2.5 fixed bottom-5 px-5">
                        <button class=" text-white font-bold bg-[#104FFF] px-[26px] py-2 rounded-lg">Assign</button>
                        <button class=" text-[#3A3A3A] border border-[#D1D5D8] px-[26px] py-2 rounded-lg">Assign & Assign Another</button>
                        <button class=" text-[#3A3A3A] border border-[#D1D5D8] px-[26px] py-2 rounded-lg">Cancel</button>
                    </div>
                </div>
            </x-filament::modal> --}}

        {{-- </div> --}}

    </div>
    {{-- Personal Information Section end  --}}


</x-filament-panels::page>
