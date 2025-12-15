<?php
function getAllChildCategoryIds($parentId, &$allIds = []) {
    $CI =& get_instance();
    $CI->db->select('montessori_syllabus_category_id');
    $CI->db->from('sas_montessori_syllabus_category_info');
    $CI->db->where('category_parent_id', $parentId);
    $query = $CI->db->get();

    foreach ($query->result_array() as $row) {
        $childId = $row['montessori_syllabus_category_id'];
        $allIds[] = $childId;
        getAllChildCategoryIds($childId, $allIds); // recursive
    }

    return $allIds;
}

function updateMontessoriAreaIdForCategoryAndLessons($parentCategoryId, $newAreaId) {
    $CI =& get_instance();

    $allCategoryIds = [$parentCategoryId];
    getAllChildCategoryIds($parentCategoryId, $allCategoryIds);

    // Update categories
    $CI->db->where_in('montessori_syllabus_category_id', $allCategoryIds);
    $CI->db->update('sas_montessori_syllabus_category_info', [
        'montessori_area_id' => $newAreaId,
        'updated_by' => $CI->session->userdata('user_id') ?? 1,
        'updated_date' => date('Y-m-d H:i:s')
    ]);
    $catRows = $CI->db->affected_rows();

    // Update lessons
    $CI->db->where_in('montessori_syllabus_category_id', $allCategoryIds);
    $CI->db->update('sas_montessori_syllabus_info_v1', [
        'montessori_area_id' => $newAreaId,
        'updated_by' => $CI->session->userdata('user_id') ?? 1,
        'updated_date' => date('Y-m-d H:i:s')
    ]);
    $lessonRows = $CI->db->affected_rows();

    return [
        'updated_categories' => $catRows,
        'updated_lessons' => $lessonRows
    ];
}