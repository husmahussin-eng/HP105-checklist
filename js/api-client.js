// API Client for RBPF Checklist System
const API_BASE_URL = window.location.origin + '/api';

// Helper function to make API calls
async function apiCall(endpoint, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        }
    };
    
    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }
    
    try {
        const url = `${API_BASE_URL}/${endpoint}`;
        console.log('API Call:', method, url);
        
        const response = await fetch(url, options);
        console.log('API Response status:', response.status);
        
        const text = await response.text();
        console.log('API Response text:', text);
        
        const result = JSON.parse(text);
        return result;
    } catch (error) {
        console.error('API Error:', error);
        return {
            success: false,
            message: 'Network error: ' + error.message
        };
    }
}

// Authentication APIs
const AuthAPI = {
    async login(username, password) {
        return await apiCall('login.php', 'POST', { username, password });
    },
    
    logout() {
        sessionStorage.removeItem('rbpf_user');
        sessionStorage.removeItem('rbpf_user_id');
        sessionStorage.removeItem('rbpf_username');
        sessionStorage.removeItem('rbpf_role');
        window.location.href = 'login.html';
    },
    
    getCurrentUser() {
        return JSON.parse(sessionStorage.getItem('rbpf_user') || 'null');
    },
    
    isLoggedIn() {
        return sessionStorage.getItem('rbpf_user') !== null;
    },
    
    isSuperAdmin() {
        const user = this.getCurrentUser();
        return user && user.role === 'super_admin';
    }
};

// Users APIs
const UsersAPI = {
    async getAll() {
        return await apiCall('users.php', 'GET');
    },
    
    async create(fullName, jawatan, username, password, role = 'user') {
        const currentUser = AuthAPI.getCurrentUser();
        return await apiCall('users.php', 'POST', {
            fullName,
            jawatan,
            username,
            password,
            role,
            createdBy: currentUser ? currentUser.username : 'System'
        });
    },
    
    async resetPassword(userId, newPassword) {
        const currentUser = AuthAPI.getCurrentUser();
        return await apiCall('users.php', 'PUT', {
            userId,
            password: newPassword,
            updatedBy: currentUser ? currentUser.username : 'System'
        });
    }
};

// Activity Log APIs
const ActivityAPI = {
    async getAll(limit = 100, type = null) {
        let endpoint = `activity.php?limit=${limit}`;
        if (type) endpoint += `&type=${type}`;
        return await apiCall(endpoint, 'GET');
    },
    
    async log(action, type = 'general') {
        const currentUser = AuthAPI.getCurrentUser();
        if (!currentUser) return;
        
        return await apiCall('activity.php', 'POST', {
            userId: currentUser.user_id,
            username: currentUser.username,
            action,
            type
        });
    }
};

// Notes APIs
const NotesAPI = {
    async getByPage(activityPage) {
        return await apiCall(`notes.php?activity_page=${activityPage}`, 'GET');
    },
    
    async create(activityPage, noteText) {
        const currentUser = AuthAPI.getCurrentUser();
        if (!currentUser) return { success: false, message: 'Not logged in' };
        
        return await apiCall('notes.php', 'POST', {
            activityPage,
            noteText,
            username: currentUser.username
        });
    },
    
    async update(noteId, noteText) {
        const currentUser = AuthAPI.getCurrentUser();
        return await apiCall('notes.php', 'PUT', {
            noteId,
            noteText,
            username: currentUser ? currentUser.username : ''
        });
    },
    
    async delete(noteId) {
        const currentUser = AuthAPI.getCurrentUser();
        return await apiCall('notes.php', 'DELETE', {
            noteId,
            username: currentUser ? currentUser.username : ''
        });
    }
};

// Meetings APIs
const MeetingsAPI = {
    async getAll() {
        return await apiCall('meetings.php', 'GET');
    },
    
    async create(title, date, time, venue, chairedBy, documentName = null, documentData = null) {
        const currentUser = AuthAPI.getCurrentUser();
        return await apiCall('meetings.php', 'POST', {
            action: 'create',
            title,
            date,
            time,
            venue,
            chaired_by: chairedBy,
            document_name: documentName,
            document_data: documentData,
            created_by: currentUser ? currentUser.username : 'System'
        });
    },
    
    async delete(meetingId) {
        const currentUser = AuthAPI.getCurrentUser();
        return await apiCall('meetings.php', 'DELETE', {
            id: meetingId,
            username: currentUser ? currentUser.username : ''
        });
    }
};

// Calendar APIs
const CalendarAPI = {
    async getAll() {
        return await apiCall('calendar.php', 'GET');
    },
    
    async saveNote(month, day, noteText) {
        const currentUser = AuthAPI.getCurrentUser();
        return await apiCall('calendar.php', 'POST', {
            month,
            day,
            note_text: noteText,
            created_by: currentUser ? currentUser.username : 'System'
        });
    },
    
    async deleteNote(month, day) {
        return await apiCall('calendar.php', 'DELETE', {
            month,
            day
        });
    }
};

// Calendar Events APIs (Multiple entries per day)
const CalendarEventsAPI = {
    async getAll() {
        return await apiCall('calendar-events.php', 'GET');
    },
    
    async getByDay(month, day) {
        return await apiCall(`calendar-events.php?month=${month}&day=${day}`, 'GET');
    },
    
    async create(month, day, eventTime, eventTitle, venue) {
        const currentUser = AuthAPI.getCurrentUser();
        return await apiCall('calendar-events.php', 'POST', {
            month,
            day,
            event_time: eventTime,
            event_title: eventTitle,
            venue: venue,
            created_by: currentUser ? currentUser.username : 'System'
        });
    },
    
    async update(eventId, eventTime, eventTitle, venue) {
        const currentUser = AuthAPI.getCurrentUser();
        return await apiCall('calendar-events.php', 'PUT', {
            id: eventId,
            event_time: eventTime,
            event_title: eventTitle,
            venue: venue,
            username: currentUser ? currentUser.username : ''
        });
    },
    
    async delete(eventId) {
        const currentUser = AuthAPI.getCurrentUser();
        return await apiCall('calendar-events.php', 'DELETE', {
            id: eventId,
            username: currentUser ? currentUser.username : ''
        });
    }
};

// Backup APIs
const BackupAPI = {
    async getAll() {
        return await apiCall('backup.php', 'GET');
    },
    
    async create(backupName) {
        const currentUser = AuthAPI.getCurrentUser();
        return await apiCall('backup.php?action=create', 'POST', {
            backupName,
            createdBy: currentUser ? currentUser.username : 'System'
        });
    },
    
    async download(backupId) {
        return await apiCall('backup.php?action=download', 'POST', { backupId });
    },
    
    async restore(backupId) {
        const currentUser = AuthAPI.getCurrentUser();
        return await apiCall('backup.php?action=restore', 'POST', {
            backupId,
            restoredBy: currentUser ? currentUser.username : 'System'
        });
    }
};

// Budget APIs
const BudgetAPI = {
    async getAll() {
        return await apiCall('budget.php', 'GET');
    },
    
    async save(items) {
        const currentUser = AuthAPI.getCurrentUser();
        return await apiCall('budget.php', 'POST', {
            items: items,
            username: currentUser ? currentUser.username : 'System'
        });
    }
};

// Export APIs
window.AuthAPI = AuthAPI;
window.UsersAPI = UsersAPI;
window.ActivityAPI = ActivityAPI;
window.NotesAPI = NotesAPI;
window.MeetingsAPI = MeetingsAPI;
window.CalendarAPI = CalendarAPI;
window.CalendarEventsAPI = CalendarEventsAPI;
window.BudgetAPI = BudgetAPI;
window.BackupAPI = BackupAPI;

