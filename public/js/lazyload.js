$(document).ready(function () {
    const content = $('.lazy-load-content');

    if (!content.length) {
        $(window).off('scroll');
        return;
    }

    function debounce(fn, wait) {
        let timeout;
        return function () {
            clearTimeout(timeout);
            timeout = setTimeout(fn, wait);
        };
    }

    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return rect.bottom >= 0 && rect.top <= window.innerHeight;
    }

    function loadContent(section) {
        if (section.length && !section.hasClass('loaded')) {
            $.ajax({
                url: section.data('src'),
                type: 'POST',
                success: function (data) {
                    const parentSection = section.closest('.editor-section');
                    const img = parentSection.find('img.lazy-load');

                    if (img.length) {
                        img.attr('src', img.data('src'));
                    }

                    section.html(data).addClass('loaded');

                    function createPlaceholder() {
                        return $('<div class="placeholder"></div>');
                    }

                    function removeAllPlaceholders() {
                        $('.placeholder').remove();
                    }

                    const $groups = $('[data-groupnumber]');

                    function addGlobalPlaceholders() {
                        $groups.each(function () {
                            const group = $(this);
                            const nextGroup = group.next('[data-groupnumber]');

                            if (nextGroup.length && nextGroup.prev('.placeholder').length === 0) {
                                nextGroup.before(createPlaceholder());
                            }
                        });

                        const container = $('.control-container');

                        if (container.children().first().not('.placeholder').length) {
                            container.prepend(createPlaceholder());
                        }

                        if (container.children().last().not('.placeholder').length) {
                            container.append(createPlaceholder());
                        }
                    }

                    $groups.each(function () {
                        new Sortable(this, {
                            group: {
                                name: 'shared',
                                put: function (to, from, item) {
                                    return !$(item).attr('data-groupnumber') || !$(to.el).closest('[data-groupnumber]').length;
                                }
                            },
                            animation: 150,
                            onStart: function (evt) {
                                removeAllPlaceholders();
                                addGlobalPlaceholders();
                            },
                            onEnd: function (evt) {
                                removeAllPlaceholders();
                                console.log(`Moved item within group ${evt.from.id}`);
                            }
                        });
                    });

                    $('.control-container > div').attr('draggable', true).each(function () {
                        new Sortable(this, {
                            group: 'shared',
                            animation: 150,
                            onStart: function (evt) {
                                removeAllPlaceholders();
                                addGlobalPlaceholders();
                            },
                            onEnd: function (evt) {
                                removeAllPlaceholders();
                                console.log(`Moved item from ${evt.from.id} to ${evt.to.id}`);
                            }
                        });
                    });
                },
            });
        }
    }

    function onScroll() {
        content.each(function () {
            const section = $(this);
            if (isInViewport(this)) {
                loadContent(section);
            }
        });
    }

    content.slice(0, 5).each(function () {
        loadContent($(this));
    });

    $(window).scroll(debounce(onScroll, 200));
    onScroll();
});
