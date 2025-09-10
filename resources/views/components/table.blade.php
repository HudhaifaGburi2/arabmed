@props([
    'headers' => [],
    'data' => [],
    'striped' => true,
    'hover' => true,
    'bordered' => false,
    'responsive' => true,
    'size' => 'default', // sm, default, lg
    'sortable' => false,
    'searchable' => false,
    'paginated' => false,
    'selectable' => false,
    'class' => ''
])

@php
    $tableClasses = [
        'table',
        $striped ? 'table-striped' : '',
        $hover ? 'table-hover' : '',
        $bordered ? 'table-bordered' : '',
        'table-' . $size,
        $class
    ];
    
    $wrapperClasses = [
        'table-wrapper',
        $responsive ? 'table-responsive' : ''
    ];
@endphp

<div class="{{ implode(' ', array_filter($wrapperClasses)) }}">
    
    @if($searchable)
        <div class="table-search">
            <div class="search-input-group">
                <input type="text" class="form-control" placeholder="البحث..." data-table-search>
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="material-icons">search</i>
                    </span>
                </div>
            </div>
        </div>
    @endif

    <table class="{{ implode(' ', array_filter($tableClasses)) }}" data-table>
        
        @if(count($headers) > 0)
            <thead class="table-header">
                <tr>
                    @if($selectable)
                        <th class="table-select-all">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" data-select-all>
                                <label class="form-check-label"></label>
                            </div>
                        </th>
                    @endif
                    
                    @foreach($headers as $key => $header)
                        @php
                            $headerData = is_array($header) ? $header : ['title' => $header, 'key' => $key];
                            $sortKey = $headerData['key'] ?? $key;
                            $sortable = $sortable && ($headerData['sortable'] ?? true);
                        @endphp
                        
                        <th class="table-header-cell {{ $sortable ? 'sortable' : '' }}" 
                            @if($sortable) data-sort="{{ $sortKey }}" @endif>
                            <div class="header-content">
                                <span class="header-title">{{ $headerData['title'] }}</span>
                                @if($sortable)
                                    <div class="sort-icons">
                                        <i class="material-icons sort-asc">keyboard_arrow_up</i>
                                        <i class="material-icons sort-desc">keyboard_arrow_down</i>
                                    </div>
                                @endif
                            </div>
                        </th>
                    @endforeach
                    
                    @isset($actions)
                        <th class="table-actions-header">الإجراءات</th>
                    @endisset
                </tr>
            </thead>
        @endif

        <tbody class="table-body">
            @if(isset($slot) && !empty(trim($slot)))
                {{ $slot }}
            @elseif(count($data) > 0)
                @foreach($data as $rowIndex => $row)
                    <tr class="table-row" data-row-index="{{ $rowIndex }}">
                        @if($selectable)
                            <td class="table-select">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input row-select" 
                                           value="{{ $row['id'] ?? $rowIndex }}">
                                    <label class="form-check-label"></label>
                                </div>
                            </td>
                        @endif
                        
                        @foreach($headers as $key => $header)
                            @php
                                $headerData = is_array($header) ? $header : ['key' => $key];
                                $cellKey = $headerData['key'] ?? $key;
                                $cellValue = $row[$cellKey] ?? '';
                            @endphp
                            
                            <td class="table-cell" data-label="{{ $headerData['title'] ?? $key }}">
                                @if(isset($headerData['render']))
                                    {!! $headerData['render']($cellValue, $row, $rowIndex) !!}
                                @else
                                    {{ $cellValue }}
                                @endif
                            </td>
                        @endforeach
                        
                        @isset($actions)
                            <td class="table-actions">
                                {{ $actions($row, $rowIndex) }}
                            </td>
                        @endisset
                    </tr>
                @endforeach
            @else
                <tr class="table-empty">
                    <td colspan="{{ count($headers) + ($selectable ? 1 : 0) + (isset($actions) ? 1 : 0) }}" 
                        class="text-center py-4">
                        <div class="empty-state">
                            <i class="material-icons empty-icon">inbox</i>
                            <p class="empty-text">لا توجد بيانات للعرض</p>
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
        
    </table>

    @if($paginated && isset($pagination))
        <div class="table-pagination">
            {{ $pagination }}
        </div>
    @endif

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('[data-table]');
    if (!table) return;
    
    const searchInput = document.querySelector('[data-table-search]');
    const selectAllCheckbox = document.querySelector('[data-select-all]');
    const rowCheckboxes = document.querySelectorAll('.row-select');
    const sortableHeaders = document.querySelectorAll('.sortable[data-sort]');
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr:not(.table-empty)');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
    
    // Select all functionality
    if (selectAllCheckbox && rowCheckboxes.length > 0) {
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                checkbox.dispatchEvent(new Event('change'));
            });
        });
        
        // Update select all when individual checkboxes change
        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checkedCount = document.querySelectorAll('.row-select:checked').length;
                selectAllCheckbox.checked = checkedCount === rowCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < rowCheckboxes.length;
            });
        });
    }
    
    // Sorting functionality
    let currentSort = { column: null, direction: null };
    
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const sortKey = this.dataset.sort;
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(.table-empty)'));
            
            // Determine sort direction
            if (currentSort.column === sortKey) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.direction = 'asc';
            }
            currentSort.column = sortKey;
            
            // Update header classes
            sortableHeaders.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));
            this.classList.add('sort-' + currentSort.direction);
            
            // Sort rows
            const columnIndex = Array.from(this.parentNode.children).indexOf(this);
            rows.sort((a, b) => {
                const aValue = a.children[columnIndex].textContent.trim();
                const bValue = b.children[columnIndex].textContent.trim();
                
                // Try to parse as numbers
                const aNum = parseFloat(aValue);
                const bNum = parseFloat(bValue);
                
                let comparison = 0;
                if (!isNaN(aNum) && !isNaN(bNum)) {
                    comparison = aNum - bNum;
                } else {
                    comparison = aValue.localeCompare(bValue, 'ar');
                }
                
                return currentSort.direction === 'asc' ? comparison : -comparison;
            });
            
            // Reorder DOM
            rows.forEach(row => tbody.appendChild(row));
        });
    });
});
</script>
@endpush
