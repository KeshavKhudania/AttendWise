(function($) {
    'use strict';

    // jQuery plugin for searchable select
    $.fn.searchableSelect = function(options) {
        const defaults = {
            placeholder: 'Search and select...',
            noResultsText: 'No results found'
        };
        
        const settings = $.extend({}, defaults, options);

        return this.each(function() {
            const $originalSelect = $(this);
            
            // Skip if already initialized
            if ($originalSelect.data('searchable-initialized')) {
                return;
            }
            
            const searchableSelect = new SearchableSelect($originalSelect, settings);
            $originalSelect.data('searchable-initialized', true);
        });
    };

    class SearchableSelect {
        constructor($originalSelect, settings) {
            this.$originalSelect = $originalSelect;
            this.settings = settings;
            this.options = [];
            this.filteredOptions = [];
            this.isOpen = false;
            this.selectedIndex = -1;
            
            this.init();
        }

        init() {
            this.extractOptions();
            this.createWrapper();
            this.createSearchInput();
            this.createDropdown();
            this.bindEvents();
            this.$originalSelect.hide();
        }

        extractOptions() {
            this.options = [];
            this.$originalSelect.find('option').each((index, option) => {
                const $option = $(option);
                this.options.push({
                    value: $option.val(),
                    text: $option.text(),
                    selected: $option.is(':selected'),
                    element: option
                });
            });
            this.filteredOptions = [...this.options];
        }

        createWrapper() {
            this.$wrapper = $('<div class="msc-ipd-searchable-wrapper"></div>');
            this.$originalSelect.before(this.$wrapper);
            this.$wrapper.append(this.$originalSelect);
        }

        createSearchInput() {
            this.$inputContainer = $('<div class="msc-ipd-searchable-input-container"></div>');
            this.$searchInput = $('<input type="text" class="msc-ipd-searchable-input" autocomplete="off">');
            this.$arrow = $('<div class="msc-ipd-searchable-arrow">▼</div>');
            
            this.$searchInput.attr('placeholder', this.getPlaceholder());
            
            this.$inputContainer.append(this.$searchInput);
            this.$inputContainer.append(this.$arrow);
            this.$wrapper.append(this.$inputContainer);
        }

        createDropdown() {
            this.$dropdown = $('<div class="msc-ipd-searchable-dropdown"></div>');
            this.$wrapper.append(this.$dropdown);
            this.updateDropdown();
        }

        getPlaceholder() {
            const firstOption = this.options[0];
            if (firstOption && firstOption.value === '') {
                return firstOption.text;
            }
            return this.settings.placeholder;
        }

        updateDropdown() {
            this.$dropdown.empty();
            
            if (this.filteredOptions.length === 0) {
                const $noResults = $('<div class="msc-ipd-searchable-option msc-ipd-no-results"></div>');
                $noResults.text(this.settings.noResultsText);
                this.$dropdown.append($noResults);
                return;
            }

            this.filteredOptions.forEach((option, index) => {
                if (option.value === '' && option.text.trim() === '') return;
                
                const $optionElement = $('<div class="msc-ipd-searchable-option"></div>');
                $optionElement.text(option.text);
                $optionElement.data('value', option.value);
                $optionElement.data('index', index);
                
                if (option.selected) {
                    $optionElement.addClass('msc-ipd-selected');
                    this.$searchInput.val(option.text);
                }
                
                if (index === this.selectedIndex) {
                    $optionElement.addClass('msc-ipd-highlighted');
                }
                
                this.$dropdown.append($optionElement);
            });
        }

        filterOptions(searchTerm) {
            const term = searchTerm.toLowerCase().trim();
            if (term === '') {
                this.filteredOptions = [...this.options];
            } else {
                this.filteredOptions = this.options.filter(option => 
                    option.text.toLowerCase().includes(term)
                );
            }
            this.selectedIndex = -1;
            this.updateDropdown();
        }

        selectOption($optionElement) {
            const value = $optionElement.data('value');
            const text = $optionElement.text();
            
            // Update original select
            this.$originalSelect.val(value);
            
            // Update options array
            this.options.forEach(option => {
                option.selected = option.value === value;
            });
            
            // Clear previous selections in dropdown
            this.$dropdown.find('.msc-ipd-searchable-option').removeClass('msc-ipd-selected');
            
            // Mark as selected
            $optionElement.addClass('msc-ipd-selected');
            this.$searchInput.val(text);
            
            // Trigger change event on original select
            this.$originalSelect.trigger('change');
            
            this.closeDropdown();
        }

        openDropdown() {
            this.isOpen = true;
            this.$dropdown.show();
            this.$wrapper.addClass('msc-ipd-open');
            this.$arrow.html('▲');
            this.$searchInput.focus();
        }

        closeDropdown() {
            this.isOpen = false;
            this.$dropdown.hide();
            this.$wrapper.removeClass('msc-ipd-open');
            this.$arrow.html('▼');
            this.selectedIndex = -1;
            
            // Reset search if no option was selected
            const selectedOption = this.options.find(opt => opt.selected);
            if (selectedOption) {
                this.$searchInput.val(selectedOption.text);
            } else {
                this.$searchInput.val('');
            }
            
            this.filterOptions('');
        }

        navigateOptions(direction) {
            const $visibleOptions = this.$dropdown.find('.msc-ipd-searchable-option:not(.msc-ipd-no-results)');
            
            if (direction === 'down') {
                this.selectedIndex = Math.min(this.selectedIndex + 1, $visibleOptions.length - 1);
            } else {
                this.selectedIndex = Math.max(this.selectedIndex - 1, 0);
            }
            
            // Update highlighting
            $visibleOptions.removeClass('msc-ipd-highlighted');
            if (this.selectedIndex >= 0 && this.selectedIndex < $visibleOptions.length) {
                $visibleOptions.eq(this.selectedIndex).addClass('msc-ipd-highlighted');
            }
        }

        bindEvents() {
            // Input events
            this.$searchInput.on('input', (e) => {
                this.filterOptions($(e.target).val());
                if (!this.isOpen) this.openDropdown();
            });

            this.$searchInput.on('focus', () => {
                this.openDropdown();
            });

            this.$searchInput.on('keydown', (e) => {
                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        if (!this.isOpen) this.openDropdown();
                        this.navigateOptions('down');
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        this.navigateOptions('up');
                        break;
                    case 'Enter':
                        e.preventDefault();
                        const $highlighted = this.$dropdown.find('.msc-ipd-highlighted');
                        if ($highlighted.length && !$highlighted.hasClass('msc-ipd-no-results')) {
                            this.selectOption($highlighted);
                        }
                        break;
                    case 'Escape':
                        this.closeDropdown();
                        break;
                }
            });

            // Arrow click
            this.$arrow.on('click', (e) => {
                e.stopPropagation();
                if (this.isOpen) {
                    this.closeDropdown();
                } else {
                    this.openDropdown();
                }
            });

            // Option selection
            this.$dropdown.on('click', '.msc-ipd-searchable-option', (e) => {
                const $target = $(e.target);
                if (!$target.hasClass('msc-ipd-no-results')) {
                    this.selectOption($target);
                }
            });

            // Close on outside click
            $(document).on('click', (e) => {
                if (!this.$wrapper.is(e.target) && this.$wrapper.has(e.target).length === 0) {
                    this.closeDropdown();
                }
            });

            // Handle original select changes
            this.$originalSelect.on('change', () => {
                this.extractOptions();
                this.updateDropdown();
            });
        }
    }

    // Auto-initialize all searchable selects
    // function initSearchableSelects() {
    //     $('select.msc-ipd-searchable').each(function() {
    //         if (!$(this).data('searchable-initialized')) {
    //             $(this).searchableSelect();
    //         }
    //     });
    // }

    // Initialize on document ready and for dynamically added elements
    // $(document).ready(function() {
    //     initSearchableSelects();
        
    //     // Watch for new elements
    //     const observer = new MutationObserver(initSearchableSelects);
    //     observer.observe(document.body, { childList: true, subtree: true });
    // });

})(jQuery);
