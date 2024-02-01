<x-filament-panels::page>
        <div class="flex items-center justify-center ">
                <div class="text-center flex items-center justify-center flex-col">
                        <x-heroicon-o-check-circle class="text-[#008000ee] w-36" />
                        <span class="block text-[#008000ee]">Congratulations</span>
                        <span class=" text-[#008000ee]"> Your Response Has Been Submited </span>
                        <x-filament::section class="mt-10 shadow-md ">


                                {{-- Content --}}
                                <div class="p-3 ">
                                        <p class="py-2">Total Questions : {{$totalQuestionCount}}</p>
                                        <p class="py-2">Correct Answer : {{$correctAnswerCount}}</p>
                                        <p class="py-2">Incorrect Answer : {{$incorrectAnswerCount}}</p>
                                        <p class="py-2">Score Percentage : {{$scorePercentage}}%</p>
                                    </div>
                                    <button class="bg-blue-600 px-4 py-1 mt-1 rounded-md text-white w-fit" wire:click='redirectToCourse()'>Return to Course Overview</button>
                        </x-filament::section>
                        @if ($incorrectAnswerCount>0)

                        <div class="flex gap-2 text-black justify-between items-center cursor-pointer mt-4" wire:click='redirectToReviewAnswer()'>
                            <x-heroicon-s-clipboard-document-check class="w-6 h-6" />

                            <p class="uppercase underline ">review Incorrect Answer</p>
                        </div>
                        @endif
                </div>


        </div>
</x-filament-panels::page>
