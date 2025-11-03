mock-data.js
export const currentUser = {
    id: 1,
    name: "John Doe",
    email: "john.doe@company.com",
    department: "Engineering",
    totalVacationDays: 25,
    usedVacationDays: 7,
    remainingVacationDays: 18,
    pendingRequests: 2
};

export const userVacations = [
    {
        id: 1,
        startDate: "2024-01-15",
        endDate: "2024-01-19",
        days: 5,
        type: "Annual Leave",
        status: "approved",
        notes: "Family vacation to Hawaii"
    },
    {
        id: 2,
        startDate: "2024-03-10",
        endDate: "2024-03-10",
        days: 1,
        type: "Personal Day",
        status: "approved",
        notes: "Doctor appointment"
    },
    {
        id: 3,
        startDate: "2024-05-20",
        endDate: "2024-05-21",
        days: 2,
        type: "Sick Leave",
        status: "approved",
        notes: "Flu recovery"
    },
    {
        id: 4,
        startDate: "2024-07-01",
        endDate: "2024-07-05",
        days: 5,
        type: "Annual Leave",
        status: "pending",
        notes: "Summer vacation"
    },
    {
        id: 5,
        startDate: "2024-12-23",
        endDate: "2024-12-30",
        days: 6,
        type: "Annual Leave",
        status: "pending",
        notes: "Christmas holidays"
    }
];

export const employees = [
    {
        id: 1,
        name: "John Doe",
        email: "john.doe@company.com",
        department: "Engineering",
        position: "Software Engineer",
        totalDays: 25,
        usedDays: 7,
        remainingDays: 18,
        status: "active",
        nextVacation: "2024-07-01",
        avatar: "JD"
    },
    {
        id: 2,
        name: "Sarah Johnson",
        email: "sarah.johnson@company.com",
        department: "Marketing",
        position: "Marketing Manager",
        totalDays: 25,
        usedDays: 12,
        remainingDays: 13,
        status: "active",
        nextVacation: "2024-08-15",
        avatar: "SJ"
    },
    {
        id: 3,
        name: "Mike Chen",
        email: "mike.chen@company.com",
        department: "Engineering",
        position: "Senior Developer",
        totalDays: 30,
        usedDays: 15,
        remainingDays: 15,
        status: "on-vacation",
        nextVacation: "Currently on vacation",
        avatar: "MC"
    },
    {
        id: 4,
        name: "Emily Rodriguez",
        email: "emily.rodriguez@company.com",
        department: "Sales",
        position: "Sales Representative",
        totalDays: 20,
        usedDays: 8,
        remainingDays: 12,
        status: "pending",
        nextVacation: "2024-06-10",
        avatar: "ER"
    },
    {
        id: 5,
        name: "David Kim",
        email: "david.kim@company.com",
        department: "HR",
        position: "HR Specialist",
        totalDays: 25,
        usedDays: 10,
        remainingDays: 15,
        status: "active",
        nextVacation: "2024-09-01",
        avatar: "DK"
    },
    {
        id: 6,
        name: "Lisa Wang",
        email: "lisa.wang@company.com",
        department: "Engineering",
        position: "DevOps Engineer",
        totalDays: 25,
        usedDays: 18,
        remainingDays: 7,
        status: "active",
        nextVacation: "2024-11-15",
        avatar: "LW"
    },
    {
        id: 7,
        name: "Alex Thompson",
        email: "alex.thompson@company.com",
        department: "Marketing",
        position: "Content Creator",
        totalDays: 22,
        usedDays: 5,
        remainingDays: 17,
        status: "on-vacation",
        nextVacation: "Currently on vacation",
        avatar: "AT"
    },
    {
        id: 8,
        name: "Maria Garcia",
        email: "maria.garcia@company.com",
        department: "Sales",
        position: "Sales Manager",
        totalDays: 28,
        usedDays: 14,
        remainingDays: 14,
        status: "pending",
        nextVacation: "2024-07-20",
        avatar: "MG"
    }
];

export const vacationTypes = [
    "Annual Leave",
    "Sick Leave",
    "Personal Day",
    "Maternity/Paternity Leave",
    "Emergency Leave",
    "Bereavement Leave"
];

export const departments = [
    "Engineering",
    "Marketing",
    "Sales",
    "HR",
    "Finance",
    "Operations"
];

// Helper functions
export function getVacationsByDateRange(startDate, endDate) {
    return userVacations.filter(vacation => {
        const vacStart = new Date(vacation.startDate);
        const vacEnd = new Date(vacation.endDate);
        const rangeStart = new Date(startDate);
        const rangeEnd = new Date(endDate);
       
        return (vacStart >= rangeStart && vacStart <= rangeEnd) ||
               (vacEnd >= rangeStart && vacEnd <= rangeEnd) ||
               (vacStart <= rangeStart && vacEnd >= rangeEnd);
    });
}

export function getVacationsByMonth(year, month) {
    return userVacations.filter(vacation => {
        const vacStart = new Date(vacation.startDate);
        const vacEnd = new Date(vacation.endDate);
       
        return (vacStart.getFullYear() === year && vacStart.getMonth() === month) ||
               (vacEnd.getFullYear() === year && vacEnd.getMonth() === month);
    });
}

export function getVacationsByYear(year) {
    return userVacations.filter(vacation => {
        const vacStart = new Date(vacation.startDate);
        return vacStart.getFullYear() === year;
    });
}

export function isDateInVacation(date) {
    return userVacations.some(vacation => {
        const vacStart = new Date(vacation.startDate);
        const vacEnd = new Date(vacation.endDate);
        const checkDate = new Date(date);
       
        return checkDate >= vacStart && checkDate <= vacEnd && vacation.status === 'approved';
    });
}

export function isDatePendingVacation(date) {
    return userVacations.some(vacation => {
        const vacStart = new Date(vacation.startDate);
        const vacEnd = new Date(vacation.endDate);
        const checkDate = new Date(date);
       
        return checkDate >= vacStart && checkDate <= vacEnd && vacation.status === 'pending';
    });
}