<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->
          <!-- COMPANY -->
          <div>
            <h2 class="text-md font-semibold py-2">
                1. Company Setup
            </h2>
            <p>
                The initial setup of a company profile  is a crucial step that brings overall better management of the workforce. Ensure to follow the steps using the link below Or fill the from
            </p>
            <a  href="/settings/companies" target="_blank" class="text-blue-600">Company Setup</a>
        </div>
    </div>
</x-dynamic-component>
