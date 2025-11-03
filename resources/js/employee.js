import {
    currentUser,
    userVacations,
    employees,
    getVacationsByMonth,
    getVacationsByYear,
    isDateInVacation,
    isDatePendingVacation
} from '.mock-data.js';

class VacationManagementSystem {
    constructor() {
        this.currentMode = 'user';
        this.currentView = 'calendar';
        this.currentYear = new Date().getFullYear();
        this.currentMonth = new Date().getMonth();
        this.filteredEmployees = [...employees];
       
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.renderUserDashboard();
        this.renderCalendar();
        this.updateUserStats();
    }

    renderUserDashboard() {
        // This method ensures the user dashboard is properly initialized
        // The HTML structure is already in place, so we just need to update the data
        this.updateUserStats();
        this.renderCalendar();
    }

    setupEventListeners() {
        // Mode switching
        const userModeBtn = document.getElementById('userModeBtn');
        const adminModeBtn = document.getElementById('adminModeBtn');
       
        if (userModeBtn) {
            userModeBtn.addEventListener('click', () => this.switchMode('user'));
        }
        if (adminModeBtn) {
            adminModeBtn.addEventListener('click', () => this.switchMode('admin'));
        }
       
        // View toggle
        const viewToggle = document.getElementById('viewToggle');
        if (viewToggle) {
            viewToggle.addEventListener('click', () => this.toggleView());
        }
       
        // Filters
        const yearFilter = document.getElementById('yearFilter');
        const monthFilter = document.getElementById('monthFilter');
       
        if (yearFilter) {
            yearFilter.addEventListener('change', (e) => this.filterByYear(e.target.value));
        }
        if (monthFilter) {
            monthFilter.addEventListener('change', (e) => this.filterByMonth(e.target.value));
        }
       
        // Admin filters
        const departmentFilter = document.getElementById('departmentFilter');
        const statusFilter = document.getElementById('statusFilter');
       
        if (departmentFilter) {
            departmentFilter.addEventListener('change', () => this.filterEmployees());
        }
        if (statusFilter) {
            statusFilter.addEventListener('change', () => this.filterEmployees());
        }
    }

    switchMode(mode) {
        this.currentMode = mode;
       
        // Update button states
        const userBtn = document.getElementById('userModeBtn');
        const adminBtn = document.getElementById('adminModeBtn');
       
        if (!userBtn || !adminBtn) return;
       
        if (mode === 'user') {
            userBtn.className = 'px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 bg-blue-500 text-white';
            adminBtn.className = 'px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 text-gray-600 hover:text-gray-900';
           
            const userDashboard = document.getElementById('userDashboard');
            const adminDashboard = document.getElementById('adminDashboard');
           
            if (userDashboard) userDashboard.classList.remove('hidden');
            if (adminDashboard) adminDashboard.classList.add('hidden');
           
            this.renderUserDashboard();
        } else {
            adminBtn.className = 'px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 bg-blue-500 text-white';
            userBtn.className = 'px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 text-gray-600 hover:text-gray-900';
           
            const userDashboard = document.getElementById('userDashboard');
            const adminDashboard = document.getElementById('adminDashboard');
           
            if (userDashboard) userDashboard.classList.add('hidden');
            if (adminDashboard) adminDashboard.classList.remove('hidden');
           
            this.renderAdminDashboard();
        }
    }

    toggleView() {
        const calendarView = document.getElementById('calendarView');
        const tableView = document.getElementById('tableView');
        const toggleBtn = document.getElementById('viewToggle');
       
        if (!calendarView || !tableView || !toggleBtn) return;
       
        if (this.currentView === 'calendar') {
            this.currentView = 'table';
            calendarView.classList.add('hidden');
            tableView.classList.remove('hidden');
            toggleBtn.innerHTML = '<i class="fas fa-calendar mr-2"></i>Calendar View';
            this.renderVacationTable();
        } else {
            this.currentView = 'calendar';
            calendarView.classList.remove('hidden');
            tableView.classList.add('hidden');
            toggleBtn.innerHTML = '<i class="fas fa-table mr-2"></i>Table View';
        }
    }

    updateUserStats() {
        const daysRemainingEl = document.getElementById('daysRemaining');
        const daysUsedEl = document.getElementById('daysUsed');
        const pendingRequestsEl = document.getElementById('pendingRequests');
       
        if (daysRemainingEl) daysRemainingEl.textContent = currentUser.remainingVacationDays;
        if (daysUsedEl) daysUsedEl.textContent = currentUser.usedVacationDays;
        if (pendingRequestsEl) pendingRequestsEl.textContent = currentUser.pendingRequests;
    }

    renderCalendar() {
        const calendar = document.getElementById('calendar');
        if (!calendar) return;
       
        const yearFilter = document.getElementById('yearFilter');
        const monthFilter = document.getElementById('monthFilter');
       
        if (!yearFilter || !monthFilter) return;
       
        const year = parseInt(yearFilter.value);
        const monthFilterValue = monthFilter.value;
       
        if (monthFilterValue === '') {
            // Show year view with all months
            this.renderYearView(calendar, year);
        } else {
            // Show specific month calendar
            this.renderMonthView(calendar, year, parseInt(monthFilterValue));
        }
    }

    renderMonthView(container, year, month) {
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());
       
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December'];
       
        let html = `
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">${monthNames[month]} ${year}</h3>
                    <button onclick="vacationSystem.showYearView()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Year View
                    </button>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="calendar-grid">
                        <div class="calendar-header">Sunday</div>
                        <div class="calendar-header">Monday</div>
                        <div class="calendar-header">Tuesday</div>
                        <div class="calendar-header">Wednesday</div>
                        <div class="calendar-header">Thursday</div>
                        <div class="calendar-header">Friday</div>
                        <div class="calendar-header">Saturday</div>
        `;
       
        const today = new Date();
        const currentDate = new Date(startDate);
       
        for (let i = 0; i < 42; i++) {
            const isCurrentMonth = currentDate.getMonth() === month;
            const isToday = currentDate.toDateString() === today.toDateString();
            const isVacation = isDateInVacation(currentDate);
            const isPending = isDatePendingVacation(currentDate);
           
            let dayClass = 'calendar-day';
            if (!isCurrentMonth) dayClass += ' other-month';
            if (isToday) dayClass += ' today';
            if (isVacation) dayClass += ' vacation';
            if (isPending) dayClass += ' pending';
           
            let events = '';
            if (isVacation) {
                events = '<div class="calendar-day-events"><span class="vacation-dot approved"></span>Vacation</div>';
            } else if (isPending) {
                events = '<div class="calendar-day-events"><span class="vacation-dot pending"></span>Pending</div>';
            }
           
            html += `
                <div class="${dayClass}">
                    <div class="calendar-day-number">${currentDate.getDate()}</div>
                    ${events}
                </div>
            `;
           
            currentDate.setDate(currentDate.getDate() + 1);
        }
       
        html += '</div></div></div>';
        container.innerHTML = html;
    }

    renderYearView(container, year) {
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December'];
       
        let html = `
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">${year} Overview</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        `;
       
        for (let month = 0; month < 12; month++) {
            const monthVacations = getVacationsByMonth(year, month);
            const approvedDays = monthVacations
                .filter(v => v.status === 'approved')
                .reduce((sum, v) => sum + v.days, 0);
            const pendingDays = monthVacations
                .filter(v => v.status === 'pending')
                .reduce((sum, v) => sum + v.days, 0);
           
            html += `
                <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow cursor-pointer"
                     onclick="vacationSystem.selectMonth(${month})">
                    <h4 class="font-semibold text-gray-900 mb-3">${monthNames[month]}</h4>
                    <div class="space-y-2">
                        ${approvedDays > 0 ? `
                            <div class="flex items-center text-sm">
                                <span class="vacation-dot approved"></span>
                                <span class="text-green-700 font-medium">${approvedDays} days approved</span>
                            </div>
                        ` : ''}
                        ${pendingDays > 0 ? `
                            <div class="flex items-center text-sm">
                                <span class="vacation-dot pending"></span>
                                <span class="text-yellow-700 font-medium">${pendingDays} days pending</span>
                            </div>
                        ` : ''}
                        ${approvedDays === 0 && pendingDays === 0 ? `
                            <div class="text-sm text-gray-500">No vacation days</div>
                        ` : ''}
                    </div>
                    <div class="mt-3 text-xs text-blue-600 font-medium">
                        <i class="fas fa-calendar-alt mr-1"></i>View Calendar
                    </div>
                </div>
            `;
        }
       
        html += '</div></div>';
        container.innerHTML = html;
    }

    selectMonth(month) {
        const monthFilter = document.getElementById('monthFilter');
        if (monthFilter) {
            monthFilter.value = month;
            this.renderCalendar();
        }
    }

    showYearView() {
        const monthFilter = document.getElementById('monthFilter');
        if (monthFilter) {
            monthFilter.value = '';
            this.renderCalendar();
        }
    }

    renderVacationTable() {
        const tbody = document.getElementById('vacationTableBody');
        if (!tbody) return;
       
        const yearFilter = document.getElementById('yearFilter');
        const monthFilter = document.getElementById('monthFilter');
       
        if (!yearFilter || !monthFilter) return;
       
        const year = parseInt(yearFilter.value);
        const monthFilterValue = monthFilter.value;
       
        let filteredVacations = getVacationsByYear(year);
       
        if (monthFilterValue !== '') {
            filteredVacations = getVacationsByMonth(year, parseInt(monthFilterValue));
        }
       
        const html = filteredVacations.map(vacation => {
            const startDate = new Date(vacation.startDate).toLocaleDateString();
            const endDate = new Date(vacation.endDate).toLocaleDateString();
            const dateRange = startDate === endDate ? startDate : `${startDate} - ${endDate}`;
           
            return `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${dateRange}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vacation.days}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vacation.type}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="status-badge status-${vacation.status}">${vacation.status}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">${vacation.notes}</td>
                </tr>
            `;
        }).join('');
       
        tbody.innerHTML = html;
    }

    renderAdminDashboard() {
        this.renderEmployeeTable();
    }

    renderEmployeeTable() {
        const tbody = document.getElementById('employeeTableBody');
        if (!tbody) return;
       
        const html = this.filteredEmployees.map(employee => {
            const usagePercentage = (employee.usedDays / employee.totalDays) * 100;
           
            return `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-medium text-sm">
                                ${employee.avatar}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${employee.name}</div>
                                <div class="text-sm text-gray-500">${employee.position}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${employee.department}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${employee.usedDays}/${employee.totalDays}</div>
                        <div class="progress-bar mt-1">
                            <div class="progress-fill" style="width: ${usagePercentage}%"></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${employee.remainingDays}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="status-badge status-${employee.status}">${employee.status}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${employee.nextVacation}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                        <button class="text-green-600 hover:text-green-900">Approve</button>
                    </td>
                </tr>
            `;
        }).join('');
       
        tbody.innerHTML = html;
    }

    filterByYear(year) {
        this.currentYear = parseInt(year);
        this.renderCalendar();
        if (this.currentView === 'table') {
            this.renderVacationTable();
        }
    }

    filterByMonth(month) {
        this.renderCalendar();
        if (this.currentView === 'table') {
            this.renderVacationTable();
        }
    }

    filterEmployees() {
        const departmentFilter = document.getElementById('departmentFilter');
        const statusFilter = document.getElementById('statusFilter');
       
        if (!departmentFilter || !statusFilter) return;
       
        const departmentValue = departmentFilter.value;
        const statusValue = statusFilter.value;
       
        this.filteredEmployees = employees.filter(employee => {
            const matchesDepartment = !departmentValue || employee.department === departmentValue;
            const matchesStatus = !statusValue || employee.status === statusValue;
           
            return matchesDepartment && matchesStatus;
        });
       
        this.renderEmployeeTable();
    }
}

// Initialize the system when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.vacationSystem = new VacationManagementSystem();
});

// Export for global access
window.VacationManagementSystem = VacationManagementSystem; 