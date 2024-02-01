<p>Rating score</p><br>
<div class="flex">
    {{-- {{ $getState() }} --}}
    @for ($i = 0; $i < $getState(); $i++)
    <img src="/icon/black-star.svg" alt="" class="cursor-pointer">
    @endfor
    @for ($i = 0; $i < 5-$getState(); $i++)
    <img src="/icon/gray-star.svg" alt="" class="cursor-pointer">
    @endfor
    {{-- @endfor --}}
</div>

