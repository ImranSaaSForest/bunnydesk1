<div>
    <x-filament::modal id="edit-user">
        <x-slot name="heading">
            Custom Variables
        </x-slot>
        <form wire:submit='createCustomVariable'>
            <div class="grid gap-y-2 mb-2">
                <div class="flex items-center justify-between gap-x-3">
                    <label class=" inline-flex items-center gap-x-3" for="data.name">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                            Name
                            <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                        </span>
                    </label>
                </div>
                <div class="grid gap-y-2 ">
                    <div
                        class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white focus-within:ring-2 dark:bg-white/5 ring-gray-950/10 focus-within:ring-primary-600 dark:ring-white/20 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                        <div class="min-w-0 flex-1">
                            <input
                                class="fi-input block w-full border-none bg-transparent py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 ps-3 pe-3"
                                id="customVariableName" maxlength="255" type="text" wire:model="customVariableName">
                        </div>
                    </div>
                    <div class="text-red-600">@error('customVariableName') {{ $message }} @enderror</div>
                </div>
            </div>
            <div class="grid gap-y-2 mb-2">
                <div class="flex items-center justify-between gap-x-3">
                    <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="data.company_id">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                            Company
                            <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                        </span>
                    </label>
                </div>
                <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white focus-within:ring-2 dark:bg-white/5 ring-gray-950/10 focus-within:ring-primary-600 dark:ring-white/20 dark:focus-within:ring-primary-500 fi-fo-select">

                <select
                    class="fi-input block w-full border border-gray-300 bg-white rounded   py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 ps-3 pe-3"
                    name="" id="" wire:model='customVariableCompanyId'>
                    <option value="">Select an option</option>
                    @foreach ($companies as $company)
                    <option value="{{$company->id}}">{{$company->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="text-red-600">@error('customVariableCompanyId') {{ $message }} @enderror</div>

            </div>
            <div class="grid gap-y-2 mb-2">
                <div class="flex items-center justify-between gap-x-3">
                    <label class=" inline-flex items-center gap-x-3" for="data.name">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                            Value
                            <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>

                        </span>

                    </label>
                </div>
                <div class="grid gap-y-2 ">
                    <div
                        class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white focus-within:ring-2 dark:bg-white/5 ring-gray-950/10 focus-within:ring-primary-600 dark:ring-white/20 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                        <div class="min-w-0 flex-1">
                            <textarea
                                class="fi-input block w-full border-none bg-transparent py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 ps-3 pe-3"
                                id="customVariableValue" maxlength="255" type="text" wire:model="customVariableValue">
                        </textarea>
                        </div>

                    </div>
                    <div class="text-red-600">@error('customVariableValue') {{ $message }} @enderror</div>

                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-primary-600 px-2 py-1 mt-2 rounded text-white w-fit">{{$currentAction=='edit'?'Save':'Create'}}</button>
                <div class="px-2 py-1 mt-2 rounded border border-gray-300" @click="$dispatch('close-modal', { id: 'edit-user' })">Cancel</div>

            </div>

        </form>
    </x-filament::modal>
</div>
