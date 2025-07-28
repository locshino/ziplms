<div class="course-wrapper">
    <div class="course-name cursor-pointer font-semibold text-blue-600" onclick="toggleAssignments(this)">
        Toán Học
    </div>
    <ul class="assignment-list ml-5 mt-2 text-gray-600" style="display:none;">
        <li>Đại số (Hạn: 01/08/2025)</li>
        <li>Hình học (Hạn: 05/08/2025)</li>
        <li>Giải tích (Hạn: 15/08/2025)</li>
    </ul>
</div>

<script>
    function toggleAssignments(el) {
        const ul = el.nextElementSibling;
        ul.style.display = (ul.style.display === 'none' || ul.style.display === '') ? 'block' : 'none';
    }
</script>

<style>
    /* Nếu bạn muốn hover thay vì click */
    .course-wrapper:hover .assignment-list {
        display: block !important;
    }
</style>