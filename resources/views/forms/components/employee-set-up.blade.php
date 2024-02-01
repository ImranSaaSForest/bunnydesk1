<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->
          <!-- EMPLOYEE -->

          <div>
            <h2 class="text-md font-semibold py-2">
                2. Employee Setup
            </h2>
            <p>
                The second step is the employee setup that offers improved data management, automation of processes. Ensure to follow the steps using the link below:
            </p>
            <a href="/employee/employee-statuses" target="_blank" class="text-blue-600">Employee Setup</a>
        </div>
    </div>
</x-dynamic-component>
