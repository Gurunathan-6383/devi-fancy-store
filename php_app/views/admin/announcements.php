<?php
$announcements = $announcements ?? [];
$baseUrl = rtrim(env('APP_URL', ''), '/');
$filter = $_GET['filter'] ?? 'all';

$filtered = $announcements;
if ($filter === 'active') $filtered = array_filter($announcements, function($a) { return ($a['status'] ?? '') === 'active'; });
elseif ($filter === 'inactive') $filtered = array_filter($announcements, function($a) { return ($a['status'] ?? '') !== 'active'; });
?>
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Announcements</h1>
            <p class="text-gray-500 mt-1"><?= count($announcements) ?> announcement(s) total</p>
        </div>
        <button data-open-modal="announcement-modal" class="btn-primary flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Announcement</span>
        </button>
    </div>

    <div class="flex items-center space-x-2 mb-6">
        <a href="?filter=all" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?= $filter === 'all' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600' ?>">All</a>
        <a href="?filter=active" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?= $filter === 'active' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600' ?>">Active</a>
        <a href="?filter=inactive" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?= $filter === 'inactive' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600' ?>">Inactive</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/80 dark:bg-gray-700/80 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Preview</th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Schedule</th>
                        <th class="text-right px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach (array_values($filtered) as $item): ?>
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center space-x-2">
                                <div class="px-3 py-1.5 rounded-full text-xs font-semibold max-w-[220px] truncate"
                                    style="background-color: <?= htmlspecialchars($item['bg_color'] ?? '#e04a6f') ?>; color: <?= htmlspecialchars($item['text_color'] ?? '#ffffff') ?>">
                                    <?= htmlspecialchars($item['title'] ?? '') ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <p class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($item['title'] ?? '') ?></p>
                            <p class="text-sm text-gray-500 truncate max-w-[200px]"><?= htmlspecialchars($item['message'] ?? '') ?></p>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400 capitalize"><?= htmlspecialchars(str_replace('_', ' ', $item['type'] ?? 'general')) ?></span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-sm font-bold text-gray-900 dark:text-white"><?= $item['priority'] ?? 0 ?></span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?= ($item['status'] ?? '') === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <?= ($item['status'] ?? '') === 'active' ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-500">
                            <?php if (!empty($item['start_date'])): ?>
                            <div>
                                <p>From: <?= date('M d, Y', strtotime($item['start_date'])) ?></p>
                                <?php if (!empty($item['end_date'])): ?><p>To: <?= date('M d, Y', strtotime($item['end_date'])) ?></p><?php endif; ?>
                            </div>
                            <?php else: ?>
                            <span class="text-gray-400">Always</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <button onclick="toggleAnnouncementStatus(<?= $item['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="<?= ($item['status'] ?? '') === 'active' ? 'Deactivate' : 'Activate' ?>">
                                    <?php if (($item['status'] ?? '') === 'active'): ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    <?php else: ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <?php endif; ?>
                                </button>
                                <button onclick="editAnnouncement(<?= $item['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-secondary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button onclick="deleteAnnouncement(<?= $item['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($filtered) === 0): ?>
                    <tr><td colspan="7" class="px-6 py-16 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        <p class="text-lg font-medium">No announcements found</p>
                        <p class="text-sm mt-1">Create your first announcement to get started.</p>
                    </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="announcement-modal" class="modal fixed inset-0 bg-black/50 backdrop-blur-sm items-center justify-center z-50 p-4" style="display:none">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-lg shadow-2xl animate-scale-in max-h-[90vh] overflow-y-auto">
            <h2 id="announcement-modal-title" class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-6">Add Announcement</h2>
            <form id="announcement-form" class="space-y-4">
                <input type="hidden" name="id" value="">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                    <input type="text" name="title" class="input-field" placeholder="e.g. 50% Off This Weekend!" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message *</label>
                    <textarea name="message" class="input-field" rows="3" placeholder="Announcement details..." required></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                        <select name="type" class="input-field">
                            <option value="discount">Discount Offer</option>
                            <option value="festival">Festival Offer</option>
                            <option value="flash_sale">Flash Sale</option>
                            <option value="new_arrival">New Arrival</option>
                            <option value="free_shipping">Free Shipping</option>
                            <option value="general">General Announcement</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                        <input type="number" name="priority" class="input-field" min="0" value="0" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="input-field">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Background Color</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" name="bg_color" value="#e04a6f" class="w-10 h-10 rounded-lg border-0 cursor-pointer" />
                            <input type="text" name="bg_color_text" value="#e04a6f" class="input-field flex-1" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Text Color</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" name="text_color" value="#ffffff" class="w-10 h-10 rounded-lg border-0 cursor-pointer" />
                            <input type="text" name="text_color_text" value="#ffffff" class="input-field flex-1" />
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date (optional)</label>
                        <input type="datetime-local" name="start_date" class="input-field" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date (optional)</label>
                        <input type="datetime-local" name="end_date" class="input-field" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Redirect URL (optional)</label>
                    <input type="url" name="redirect_url" class="input-field" placeholder="https://example.com" />
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview</p>
                    <div id="announcement-preview" class="rounded-xl overflow-hidden" style="background-color: #e04a6f; color: #ffffff">
                        <div class="flex items-center h-10 px-4 space-x-2 text-sm">
                            <span id="preview-title" class="font-semibold">Announcement Title</span>
                            <span class="opacity-80">—</span>
                            <span id="preview-message">Announcement message</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" data-close-modal class="px-4 py-2.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">Cancel</button>
                    <button type="submit" class="btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var _announcementsData = <?= json_encode(array_values($announcements)) ?>;

function editAnnouncement(id) {
    var ann = _announcementsData.find(function(a) { return a.id === id; });
    if (!ann) return;
    var form = document.getElementById('announcement-form');
    form.querySelector('[name="id"]').value = ann.id;
    form.querySelector('[name="title"]').value = ann.title || '';
    form.querySelector('[name="message"]').value = ann.message || '';
    form.querySelector('[name="type"]').value = ann.type || 'general';
    form.querySelector('[name="status"]').value = ann.status || 'active';
    form.querySelector('[name="priority"]').value = ann.priority || 0;
    form.querySelector('[name="bg_color"]').value = ann.bg_color || '#e04a6f';
    form.querySelector('[name="bg_color_text"]').value = ann.bg_color || '#e04a6f';
    form.querySelector('[name="text_color"]').value = ann.text_color || '#ffffff';
    form.querySelector('[name="text_color_text"]').value = ann.text_color || '#ffffff';
    form.querySelector('[name="start_date"]').value = ann.start_date ? ann.start_date.slice(0, 16) : '';
    form.querySelector('[name="end_date"]').value = ann.end_date ? ann.end_date.slice(0, 16) : '';
    form.querySelector('[name="redirect_url"]').value = ann.redirect_url || '';
    document.getElementById('announcement-modal-title').textContent = 'Edit Announcement';
    form.querySelector('[type="submit"]').textContent = 'Update';
    form.querySelector('[type="submit"]').setAttribute('data-save', 'updateAnnouncement');
    form.querySelector('[type="submit"]').removeAttribute('data-create');
    updateAnnouncementPreview();
    openModal('announcement-modal');
}

document.getElementById('announcement-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = e.target;
    var id = form.querySelector('[name="id"]').value;
    var data = {
        title: form.querySelector('[name="title"]').value.trim(),
        message: form.querySelector('[name="message"]').value.trim(),
        type: form.querySelector('[name="type"]').value,
        status: form.querySelector('[name="status"]').value,
        priority: parseInt(form.querySelector('[name="priority"]').value) || 0,
        bg_color: form.querySelector('[name="bg_color"]').value,
        text_color: form.querySelector('[name="text_color"]').value,
        start_date: form.querySelector('[name="start_date"]').value || null,
        end_date: form.querySelector('[name="end_date"]').value || null,
        redirect_url: form.querySelector('[name="redirect_url"]').value || null,
    };
    if (data.start_date) data.start_date = data.start_date.replace('T', ' ') + ':00';
    if (data.end_date) data.end_date = data.end_date.replace('T', ' ') + ':00';
    var url = id ? '<?= $baseUrl ?>/api/announcements/' + id : '<?= $baseUrl ?>/api/announcements';
    var method = id ? 'PUT' : 'POST';
    fetch(url, { method: method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success) { showToast(id ? 'Announcement updated!' : 'Announcement created!'); closeModal('announcement-modal'); location.reload(); }
        else showToast(res.message || 'Failed to save', 'error');
    })
    .catch(function() { showToast('Failed to save', 'error'); });
});

function toggleAnnouncementStatus(id) {
    fetch('<?= $baseUrl ?>/api/announcements/' + id + '/toggle-status', { method: 'PATCH' })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) { showToast('Status toggled!'); location.reload(); } else showToast('Failed', 'error'); });
}

function deleteAnnouncement(id) {
    if (!confirm('Delete this announcement?')) return;
    fetch('<?= $baseUrl ?>/api/announcements/' + id, { method: 'DELETE' })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) { showToast('Announcement deleted!'); location.reload(); } else showToast('Failed to delete', 'error'); });
}

function updateAnnouncementPreview() {
    var form = document.getElementById('announcement-form');
    var title = form.querySelector('[name="title"]').value || 'Announcement Title';
    var message = form.querySelector('[name="message"]').value || 'Announcement message';
    var bgColor = form.querySelector('[name="bg_color"]').value;
    var textColor = form.querySelector('[name="text_color"]').value;
    var preview = document.getElementById('announcement-preview');
    preview.style.backgroundColor = bgColor;
    preview.style.color = textColor;
    document.getElementById('preview-title').textContent = title;
    document.getElementById('preview-message').textContent = message;
}

document.getElementById('announcement-form').querySelector('[name="title"]').addEventListener('input', updateAnnouncementPreview);
document.getElementById('announcement-form').querySelector('[name="message"]').addEventListener('input', updateAnnouncementPreview);
document.getElementById('announcement-form').querySelector('[name="bg_color"]').addEventListener('input', function(e) {
    document.querySelector('[name="bg_color_text"]').value = e.target.value;
    updateAnnouncementPreview();
});
document.getElementById('announcement-form').querySelector('[name="bg_color_text"]').addEventListener('input', function(e) {
    document.querySelector('[name="bg_color"]').value = e.target.value;
    updateAnnouncementPreview();
});
document.getElementById('announcement-form').querySelector('[name="text_color"]').addEventListener('input', function(e) {
    document.querySelector('[name="text_color_text"]').value = e.target.value;
    updateAnnouncementPreview();
});
document.getElementById('announcement-form').querySelector('[name="text_color_text"]').addEventListener('input', function(e) {
    document.querySelector('[name="text_color"]').value = e.target.value;
    updateAnnouncementPreview();
});
</script>
