<?php
$announcements = $announcements ?? [];
$baseUrl = rtrim(env('APP_URL', ''), '/');
?>
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Manage Announcements</h1>
            <p class="text-gray-500 mt-1"><?= count($announcements) ?> announcement(s) total</p>
        </div>
        <button onclick="openAnnouncementModal()" class="btn-primary flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Announcement</span>
        </button>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50/80 dark:bg-gray-700/80 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Message</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="text-right px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($announcements as $a): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <?php if (!empty($a['icon'])): ?><span class="text-xl"><?= htmlspecialchars($a['icon']) ?></span><?php endif; ?>
                            <span class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($a['message'] ?? '') ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= !empty($a['is_active']) ? 'bg-green-100 text-green-700' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' ?>">
                            <?= !empty($a['is_active']) ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?= !empty($a['created_at']) ? date('M d, Y', strtotime($a['created_at'])) : '-' ?></td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <button onclick="toggleAnnouncementStatus(<?= $a['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Toggle status">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                            <button onclick="editAnnouncement(<?= $a['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-secondary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button onclick="deleteAnnouncement(<?= $a['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($announcements) === 0): ?>
                <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">No announcements found. Create your first announcement!</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="announcement-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-lg shadow-2xl animate-scale-in">
            <h2 id="announcement-modal-title" class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-4">Add Announcement</h2>
            <form id="announcement-form" class="space-y-4">
                <input type="hidden" id="announcement-id" value="">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message</label>
                    <textarea id="announcement-message" class="input-field" rows="3" placeholder="Announcement message" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Icon (emoji, optional)</label>
                    <input type="text" id="announcement-icon" class="input-field" placeholder="e.g. 🎉, ✨, 📢" />
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="announcement-active" class="w-4 h-4 text-primary-600" checked />
                    <label for="announcement-active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</label>
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" onclick="closeAnnouncementModal()" class="px-4 py-2.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">Cancel</button>
                    <button type="submit" id="announcement-submit-btn" class="btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var announcementEditId = null;

function openAnnouncementModal() {
    announcementEditId = null;
    document.getElementById('announcement-id').value = '';
    document.getElementById('announcement-message').value = '';
    document.getElementById('announcement-icon').value = '';
    document.getElementById('announcement-active').checked = true;
    document.getElementById('announcement-modal-title').textContent = 'Add Announcement';
    document.getElementById('announcement-submit-btn').textContent = 'Create';
    document.getElementById('announcement-modal').classList.remove('hidden');
}
function closeAnnouncementModal() { document.getElementById('announcement-modal').classList.add('hidden'); }

function editAnnouncement(id) {
    announcementEditId = id;
    fetch('<?= $baseUrl ?>/api/announcements/' + id).then(function(r) { return r.json(); }).then(function(res) {
        if (res.success) {
            var a = res.data;
            document.getElementById('announcement-message').value = a.message || '';
            document.getElementById('announcement-icon').value = a.icon || '';
            document.getElementById('announcement-active').checked = !!a.is_active;
            document.getElementById('announcement-modal-title').textContent = 'Edit Announcement';
            document.getElementById('announcement-submit-btn').textContent = 'Update';
            document.getElementById('announcement-modal').classList.remove('hidden');
        } else { showToast('Failed to load announcement', 'error'); }
    });
}

document.getElementById('announcement-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var data = {
        message: document.getElementById('announcement-message').value.trim(),
        icon: document.getElementById('announcement-icon').value.trim(),
        is_active: document.getElementById('announcement-active').checked ? 1 : 0,
    };
    var url = announcementEditId ? '<?= $baseUrl ?>/api/announcements/' + announcementEditId : '<?= $baseUrl ?>/api/announcements';
    var method = announcementEditId ? 'PUT' : 'POST';
    fetch(url, { method: method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) { showToast(announcementEditId ? 'Announcement updated!' : 'Announcement created!'); closeAnnouncementModal(); location.reload(); } else showToast(res.message || 'Failed to save', 'error'); })
    .catch(function() { showToast('Failed to save', 'error'); });
});

function toggleAnnouncementStatus(id) {
    fetch('<?= $baseUrl ?>/api/announcements/' + id + '/toggle-status', { method: 'PATCH' })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) { showToast('Status toggled!'); location.reload(); } else showToast('Failed', 'error'); });
}

function deleteAnnouncement(id) {
    if (!confirm('Are you sure you want to delete this announcement?')) return;
    fetch('<?= $baseUrl ?>/api/announcements/' + id, { method: 'DELETE' })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) { showToast('Announcement deleted!'); location.reload(); } else showToast('Failed to delete', 'error'); });
}
</script>
