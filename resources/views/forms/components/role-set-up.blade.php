<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->
          <!-- ROLE -->

          <div>
            <h2 class="text-md font-semibold py-2">
            3. Role Setup
            </h2>
            <p>
                Creating roles for the employees is the final step to start with.  Ensure to follow the steps using the link below:
            </p>
            <a href="/settings/roles" target="_blank" class="text-blue-600">Role Setup</a>
        </div>
    </div>
</x-dynamic-component>
