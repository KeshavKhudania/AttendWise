class CustomSearchableSelect {
    constructor(selectElement, options = {}) {
        if (typeof selectElement === 'string') {
            this.originalSelect = document.querySelector(selectElement);
        } else {
            this.originalSelect = selectElement;
        }
        
        if (!this.originalSelect) return;
        
        this.placeholder = options.placeholder || this.originalSelect.getAttribute('placeholder') || 'Select an option...';
        this.ajaxUrl = options.ajaxUrl || this.originalSelect.dataset.ajaxUrl || null;
        
        this.isOpen = false;
        
        // For AJAX mode
        this.currentPage = 1;
        this.hasMore = false;
        this.isLoading = false;
        this.ajaxData = []; // Array of grouped data or flat data
        this.searchTimeout = null;
        this.currentQuery = '';
        
        this.init();
    }

    init() {
        this.originalSelect.style.display = 'none';
        
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'custom-searchable-select';
        
        this.trigger = document.createElement('div');
        this.trigger.className = 'custom-searchable-select-trigger';
        this.trigger.innerHTML = `
            <span class="custom-searchable-select-value">${this.placeholder}</span>
            <svg class="custom-searchable-select-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
        `;
        
        this.dropdown = document.createElement('div');
        this.dropdown.className = 'custom-searchable-select-dropdown';
        
        this.searchContainer = document.createElement('div');
        this.searchContainer.className = 'custom-searchable-select-search-container';
        this.searchInput = document.createElement('input');
        this.searchInput.type = 'text';
        this.searchInput.className = 'custom-searchable-select-search';
        this.searchInput.placeholder = 'Search...';
        this.searchContainer.appendChild(this.searchInput);
        
        this.optionsContainer = document.createElement('div');
        this.optionsContainer.className = 'custom-searchable-select-options';
        
        this.dropdown.appendChild(this.searchContainer);
        this.dropdown.appendChild(this.optionsContainer);
        
        this.wrapper.appendChild(this.trigger);
        this.wrapper.appendChild(this.dropdown);
        
        this.originalSelect.parentNode.insertBefore(this.wrapper, this.originalSelect.nextSibling);
        
        if (!this.ajaxUrl) {
            this.renderStaticOptions();
        }
        
        this.bindEvents();
        this.updateTriggerText();
    }
    
    // --- STATIC MODE ---
    renderStaticOptions(filterText = '') {
        this.optionsContainer.innerHTML = '';
        const lowerFilter = filterText.toLowerCase();
        let matchCount = 0;
        
        Array.from(this.originalSelect.children).forEach(child => {
            if (child.tagName === 'OPTGROUP') {
                const groupLabel = child.getAttribute('label');
                let groupHasMatches = false;
                
                const groupEl = document.createElement('div');
                groupEl.className = 'custom-searchable-select-optgroup';
                groupEl.textContent = groupLabel;
                
                const itemsFragment = document.createDocumentFragment();
                
                Array.from(child.children).forEach(option => {
                    if (this.addOptionElement(option.value, option.textContent, option.selected, lowerFilter, itemsFragment)) {
                        groupHasMatches = true;
                        matchCount++;
                    }
                });
                
                if (groupHasMatches) {
                    this.optionsContainer.appendChild(groupEl);
                    this.optionsContainer.appendChild(itemsFragment);
                }
                
            } else if (child.tagName === 'OPTION') {
                if (this.addOptionElement(child.value, child.textContent, child.selected, lowerFilter, this.optionsContainer)) {
                    matchCount++;
                }
            }
        });
        
        if (matchCount === 0) {
            this.showEmptyState();
        }
    }
    
    addOptionElement(value, text, isSelected, filterText, container) {
        if (!value && !filterText) return false; 
        
        if (filterText && !text.toLowerCase().includes(filterText)) {
            return false;
        }
        
        const el = document.createElement('div');
        el.className = 'custom-searchable-select-option';
        if (isSelected) el.classList.add('selected');
        el.textContent = text;
        el.dataset.value = value;
        
        el.addEventListener('click', () => {
            this.selectValue(value, text);
        });
        
        container.appendChild(el);
        return true;
    }
    
    // --- AJAX MODE ---
    async fetchAjaxOptions(isAppend = false) {
        if (this.isLoading) return;
        this.isLoading = true;
        
        if (!isAppend) {
            this.currentPage = 1;
            this.ajaxData = [];
            this.optionsContainer.innerHTML = '<div class="custom-searchable-select-empty">Loading...</div>';
        } else {
            const loader = document.createElement('div');
            loader.className = 'custom-searchable-select-empty append-loader';
            loader.textContent = 'Loading more...';
            this.optionsContainer.appendChild(loader);
        }
        
        try {
            const url = new URL(this.ajaxUrl, window.location.origin);
            url.searchParams.append('q', this.currentQuery);
            url.searchParams.append('page', this.currentPage);
            
            const response = await fetch(url.toString(), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            
            this.hasMore = data.pagination && data.pagination.more;
            
            if (!isAppend) {
                this.ajaxData = data.results;
            } else {
                this.ajaxData = this.ajaxData.concat(data.results);
            }
            
            this.renderAjaxOptions();
            
        } catch (error) {
            console.error('Error fetching select options:', error);
            if (!isAppend) {
                this.optionsContainer.innerHTML = '<div class="custom-searchable-select-empty">Error loading results</div>';
            }
        } finally {
            this.isLoading = false;
        }
    }
    
    renderAjaxOptions() {
        this.optionsContainer.innerHTML = '';
        
        if (this.ajaxData.length === 0) {
            this.showEmptyState();
            return;
        }
        
        // Group data
        const grouped = {};
        const flat = [];
        this.ajaxData.forEach(item => {
            if (item.group) {
                if (!grouped[item.group]) grouped[item.group] = [];
                grouped[item.group].push(item);
            } else {
                flat.push(item);
            }
        });
        
        // Render groups
        for (const [groupName, items] of Object.entries(grouped)) {
            const groupEl = document.createElement('div');
            groupEl.className = 'custom-searchable-select-optgroup';
            groupEl.textContent = groupName;
            this.optionsContainer.appendChild(groupEl);
            
            items.forEach(item => {
                this.addAjaxOptionElement(item, this.optionsContainer);
            });
        }
        
        // Render flat
        flat.forEach(item => {
            this.addAjaxOptionElement(item, this.optionsContainer);
        });
    }
    
    addAjaxOptionElement(item, container) {
        const isSelected = this.originalSelect.value == item.id;
        
        const el = document.createElement('div');
        el.className = 'custom-searchable-select-option';
        if (isSelected) el.classList.add('selected');
        el.textContent = item.text;
        el.dataset.value = item.id;
        
        el.addEventListener('click', () => {
            this.ensureOptionExists(item.id, item.text);
            this.selectValue(item.id, item.text);
        });
        
        container.appendChild(el);
    }
    
    ensureOptionExists(value, text) {
        let exists = Array.from(this.originalSelect.options).some(opt => opt.value == value);
        if (!exists) {
            const opt = new Option(text, value, false, false);
            this.originalSelect.appendChild(opt);
        }
    }
    
    // --- SHARED ---
    showEmptyState() {
        const emptyEl = document.createElement('div');
        emptyEl.className = 'custom-searchable-select-empty';
        emptyEl.textContent = 'No matches found';
        this.optionsContainer.appendChild(emptyEl);
    }
    
    selectValue(value, text) {
        this.originalSelect.value = value;
        this.originalSelect.dispatchEvent(new Event('change'));
        this.updateTriggerText();
        this.close();
        
        if (!this.ajaxUrl) {
            this.renderStaticOptions(); // Reset visual selection
        }
    }
    
    updateTriggerText() {
        const valueSpan = this.trigger.querySelector('.custom-searchable-select-value');
        if (this.originalSelect.selectedIndex >= 0) {
            const selectedOpt = this.originalSelect.options[this.originalSelect.selectedIndex];
            if (selectedOpt.value) {
                valueSpan.textContent = selectedOpt.textContent;
                valueSpan.style.color = 'var(--text-main, #111827)';
                return;
            }
        }
        valueSpan.textContent = this.placeholder;
        valueSpan.style.color = 'var(--text-muted, #6b7280)';
    }
    
    bindEvents() {
        this.trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        });
        
        this.searchInput.addEventListener('input', (e) => {
            if (this.ajaxUrl) {
                this.currentQuery = e.target.value;
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.fetchAjaxOptions();
                }, 300);
            } else {
                this.renderStaticOptions(e.target.value);
            }
        });
        
        // Lazy loading scroll event
        this.optionsContainer.addEventListener('scroll', () => {
            if (this.ajaxUrl && this.hasMore && !this.isLoading) {
                const { scrollTop, scrollHeight, clientHeight } = this.optionsContainer;
                // If within 20px of bottom
                if (scrollTop + clientHeight >= scrollHeight - 20) {
                    this.currentPage++;
                    this.fetchAjaxOptions(true);
                }
            }
        });
        
        document.addEventListener('click', (e) => {
            if (this.isOpen && !this.wrapper.contains(e.target)) {
                this.close();
            }
        });
    }
    
    open() {
        this.isOpen = true;
        this.wrapper.classList.add('active');
        this.searchInput.value = '';
        this.currentQuery = '';
        
        if (this.ajaxUrl) {
            // Fetch fresh results on open
            this.fetchAjaxOptions();
        } else {
            this.renderStaticOptions();
        }
        
        setTimeout(() => this.searchInput.focus(), 10);
    }
    
    close() {
        this.isOpen = false;
        this.wrapper.classList.remove('active');
    }
    
    setValue(val, text = null) {
        if (text && this.ajaxUrl) {
            this.ensureOptionExists(val, text);
        }
        this.originalSelect.value = val;
        this.updateTriggerText();
        
        if (!this.ajaxUrl) {
            this.renderStaticOptions();
        }
    }
    
    clear() {
        this.originalSelect.value = '';
        this.updateTriggerText();
        if (!this.ajaxUrl) this.renderStaticOptions();
    }
    
    destroy() {
        if(this.wrapper) {
            this.wrapper.remove();
        }
        this.originalSelect.style.display = '';
    }
}

function initCustomSearchableSelects() {
    document.querySelectorAll('[data-searchable-select]').forEach(select => {
        if (!select.customSelectInstance) {
            select.customSelectInstance = new CustomSearchableSelect(select);
        }
    });
}

document.addEventListener('DOMContentLoaded', initCustomSearchableSelects);
