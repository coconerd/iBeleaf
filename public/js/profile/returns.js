
$(document).ready(function() {
    // Filter by type
    $('.filter-row .btn[data-type]').click(function() {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        
        const type = $(this).data('type');
        filterItems();
    });

    // Filter by status 
    $('.filter-row .btn[data-status]').click(function() {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        
        filterItems();
    });

    function filterItems() {
        const selectedType = $('.filter-row .btn[data-type].active').data('type');
        const selectedStatus = $('.filter-row .btn[data-status].active').data('status');

        $('.return-item').each(function() {
            const itemType = $(this).data('type');
            const itemStatus = $(this).data('status');
            
            const typeMatch = selectedType === 'all' || itemType === selectedType;
            const statusMatch = selectedStatus === 'all' || itemStatus === selectedStatus;

            $(this).toggle(typeMatch && statusMatch);
        });

        // Show message if no items match filters
        const visibleItems = $('.return-item:visible').length;
        if (visibleItems === 0) {
            if (!$('.no-items-message').length) {
                $('.returns-list').append(`
                    <div class="text-center py-5 no-items-message">
                        <h5>Không tìm thấy yêu cầu đổi/trả hàng nào</h5>
                    </div>
                `);
            }
        } else {
            $('.no-items-message').remove();
        }
    }
});

// Image modal functionality
function showFullImage(src) {
    $('#fullImage').attr('src', src);
    $('#imageModal').modal('show');
}

// Close modal on click outside
$('#imageModal').click(function(e) {
    if (e.target === this || $(e.target).hasClass('modal-body')) {
        $(this).modal('hide');
    }
});