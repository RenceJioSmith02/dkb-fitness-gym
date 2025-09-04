<?php
require "db/db.php";
$mydb = new myDB();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if logged in
if (isset($_SESSION['auth_id']) || isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gym MS | RFID Log</title>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/sweetalert2.min.js"></script>

    <link rel="icon" href="assets/images/system_image/favicon.png" type="image/png">
    <link rel="stylesheet" href="assets/css/main.css">

    <style>
        td {
            font-size: 16px;
        }

        #logsTable td:last-child {
            font-size: 20px;
            font-weight: 800;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container flex jus-center al-center">

        <div class="content landingpage flex jus-center al-start">

            <div class="left flex flex-col jus-center al-center">

                <div class="scanner-container flex flex-col jus-center">
                    <!-- RFID Hidden Input -->
                    <input type="text" id="rfidInput" autofocus style="opacity:0; z-index: -999; position:absolute;">


                    <div class="logo-container flex flex-col jus-center al-center">
                        <img src="./assets/images/system_image/logo-nobg.png" alt="Logo">
                    </div>

                    <div class="verse-container flex al-center just-center flex-col">
                        <p><i>"I can do all this through </br>him who gives me strength"</i></p>
                        <h3>Philippians 4:13</h3>
                    </div>

                </div>

                <div class="login-container flex flex-col jus-center al-center" style="display: none;">
                    <div class="logo-container flex flex-col jus-center al-center">
                        <img src="./assets/images/system_image/logo-nobg.png" width="0px" alt="Logo">
                        <h2>DKB Fitness Gym</h2>
                    </div>
                    <p>Welcome Admin!</p>
                    <form id="loginForm" class="login-form-group flex flex-col jus-center al-start">
                        <div class="input-group">
                            <label for="username">Username</label>
                            <input type="text" name="auth_username" placeholder="Enter your username">
                        </div>
                        <div class="input-group">
                            <label for="password">Password</label>
                            <input type="password" name="auth_password" placeholder="Enter your password">
                        </div>
                        <button type="submit" class="btn-primary" style="margin-top: 20px;">Login</button>
                    </form>
                </div>

            </div>


            <div class="right">

                <div class="header flex jus-center al-center">
                    <h1>Member Visitation Record</h1>
                </div>
                <div class="top-controls landingpage flex jus-between al-center">
                    <div class="search-container">
                        <input type="text" id="searchInput" placeholder="Search...">
                        <img src="./assets/images/system_image/svg/search-icon.svg" class="search-icon" />
                    </div>

                    <button id="loginBtn" class="btn-secondary">Login</button>
                    <button id="scanBtn" class="btn-secondary" style="display: none;">Scan RFID</button>

                </div>

                <div class="table-container log">
                    <table width="100%" id="logsTable">
                        <thead>
                            <tr>
                                <th style="width: 80px;">
                                    No.
                                </th>
                                <th style="width: 100px;">
                                    Profile
                                </th>
                                <th style="width: 250px;">
                                    <button type="button" class="sort-btn" data-column="member">
                                        Member <img src="./assets/images/system_image/svg/sorting-arrow.svg"
                                            class="sort-arrow" />
                                    </button>
                                </th>
                                <th>
                                    <button type="button" class="sort-btn" data-column="time_in">
                                        Time In <img src="./assets/images/system_image/svg/sorting-arrow.svg"
                                            class="sort-arrow" />
                                    </button>
                                </th>
                                <th>
                                    <button type="button" class="sort-btn" data-column="time_out">
                                        Time Out <img src="./assets/images/system_image/svg/sorting-arrow.svg"
                                            class="sort-arrow" />
                                    </button>
                                </th>
                                <th>
                                    <button type="button" class="sort-btn" data-column="mem_type">
                                        Membership Type <img src="./assets/images/system_image/svg/sorting-arrow.svg"
                                            class="sort-arrow" />
                                    </button>
                                </th>
                                <th>
                                    Remaining Days
                                </th>
                            </tr>
                        </thead>
                        <tbody id="logTable" class="scroller-format">

                        </tbody>
                    </table>

                    <div class="pagination-container" style="text-align:right;">
                        <button id="prevPage">&laquo; Prev</button>
                        <span id="pageInfo">1 of 1</span>
                        <button id="nextPage">Next &raquo;</button>
                    </div>

                </div>

            </div>
        </div>

    </div>


    <!-- User Card Overlay -->
    <div id="userCardOverlay" class="user-card-overlay">
        <div class="user-card-container">
            <!-- Left Panel -->
            <div class="user-card-left">
                <img id="modalUserImg" src="" alt="User Image">
                <h2 id="modalUserName"></h2>
                <p id="modalUserType"></p>
            </div>

            <!-- Right Panel -->
            <div class="user-card-right">
                <h3>Profile Details</h3>
                <p><strong>Name:</strong> <span id="modalFullName"></span></p>
                <p><strong>Gender:</strong> <span id="modalUserGender"></span></p>
                <p><strong>Mobile Number:</strong> <span id="modalUserMobile"></span></p>
                <p><strong>Email:</strong> <span id="modalUserEmail"></span></p>
                <p><strong>Address:</strong> <span id="modalUserAddress"></span></p>
                <p id="modalSubscription"></p>
            </div>
        </div>
    </div>




    <script type="text/javascript">

        $(document).ready(function () {

            $("#userCardOverlay").hide();

            loadLogs();

            let triggeredDate = null;

            function checkLogoutTrigger() {
                let now = new Date();
                let options = { timeZone: "Asia/Manila", hour12: false };
                let phTime = new Date(now.toLocaleString("en-US", options));

                let todayStr = phTime.toISOString().split("T")[0];

                // reset trigger if new day
                if (triggeredDate !== todayStr) {
                    triggeredDate = null;
                }

                // Target = today at 20:20 (8:20 PM)
                let target = new Date(phTime);
                target.setHours(20, 20, 0, 0);

                // if not yet triggered today and time >= 8:30pm
                if (!triggeredDate && phTime >= target) {
                    triggeredDate = todayStr;

                    $.post("db/request.php", { action: "auto_logout_and_export" }, function (response) {
                        console.log("Auto logout + export done:", response);
                    }, "json");
                }
            }

            // check every 30 sec for better accuracy
            setInterval(checkLogoutTrigger, 30000);
        });

        // =============================
        // Login process
        // =============================
        $(document).on("submit", "#loginForm", function (e) {
            e.preventDefault();

            $.ajax({
                url: "db/request.php",
                type: "POST",
                data: $(this).serialize() + "&action=login",
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        window.location.href = response.redirect;
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                    alert("Something went wrong, please try again.");
                }
            });
        });


        // =============================
        // Load logs (today or search)
        // =============================
        let logsData = [];
        let currentLogPage = 1;
        let totalLogPages = 1;
        let currentSort = { column: null, asc: true };

        function loadLogs(keyword = null) {

            updateExpiredMemberships();

            let data = {};
            if (keyword === null || keyword === "") {
                data = { action: "get_logs", page: currentLogPage };
            } else {
                data = { action: "search_logs", search_logs: keyword, page: currentLogPage };
            }

            $.post("db/request.php", data, function (res) {
                let response = typeof res === "string" ? JSON.parse(res) : res;
                logsData = response.data;
                totalLogPages = Math.ceil(response.total / response.limit);

                renderLogs(logsData);
                updateLogPagination(response.page, totalLogPages);
            });
        }

        function updateExpiredMemberships() {
            $.post("db/request.php", { action: "update_expired_memberships" }, function (res) {
                let response = typeof res === "string" ? JSON.parse(res) : res;
                if (response.status === "success") {
                    console.log("Expired memberships updated successfully");
                } else {
                    console.warn("Membership update error:", response.message);
                }
            });
        }


        // format time string to 12-hour with am/pm
        function formatTo12HourPH(timeStr) {
            if (!timeStr || timeStr === "0000-00-00 00:00:00") {
                return "Not yet timed out";
            }

            // Extract only the HH:mm:ss part (in case the string includes date)
            let timeOnly = timeStr.split(" ")[1] || timeStr;

            let parts = timeOnly.split(":");
            if (parts.length < 2) return timeStr;

            let date = new Date();
            date.setHours(parseInt(parts[0], 10));
            date.setMinutes(parseInt(parts[1], 10));
            date.setSeconds(parts[2] ? parseInt(parts[2], 10) : 0);

            return new Intl.DateTimeFormat("en-US", {
                timeZone: "Asia/Manila",
                hour: "numeric",
                minute: "2-digit",
                hour12: true
            }).format(date).toLowerCase();
        }


        // Render table
        function renderLogs(datas) {
            let tBody = "";
            let cnt = (currentLogPage - 1) * 15 + 1;

            // today at midnight (PH) for clean day diffs
            const today = getTodayPH();

            datas.forEach((d, index) => {
                const endParts = getYMDParts(d.mem_end_date);
                const remainingDays = endParts ? daysBetween(today, endParts) : 0;

                const rowId = `remaining-${index}`;

                tBody += `<tr>
                        <td style="width: 80px;">${cnt++}</td>
                        <td class="log-profile">
                            <img src="assets/images/user_image/${d.user_image ?? 'default.png'}" 
                                alt="Profile Image" class="log-img">
                        </td>
                        <td style="width: 250px;">${d.user_fname} ${d.user_lname}</td>
                        <td>${formatTo12HourPH(d.log_time_in)}</td>
                        <td>${formatTo12HourPH(d.log_time_out)}</td>
                        <td>${d.mem_type ?? ''}</td>
                        <td id="${rowId}">${remainingDays}</td>
                    </tr>`;
            });

            if (datas.length === 0) {
                tBody = `<tr><td colspan="7" style="text-align:center;">No logs found</td></tr>`;
            }

            $("#logTable").html(tBody);

            // from end-of-month (of the mem_end_date month) down to remaining days
            datas.forEach((d, index) => {
                const endParts = getYMDParts(d.mem_end_date);
                if (!endParts) return;

                const remainingDays = daysBetween(today, endParts);
                const el = document.getElementById(`remaining-${index}`);
                if (!el) return;

                // If expired or no remaining days, just show 0/no animation
                if (remainingDays <= 0) {
                    el.textContent = 0;
                    return;
                }

                // Days in the month of the membership end date
                const eomDays = daysInMonth(endParts.y, endParts.m);

                // Ensure start >= end (for far future dates, start from remainingDays)
                let start = Math.max(eomDays, remainingDays);
                const end = remainingDays;

                // quick, smooth animation regardless of gap size
                const duration = 1000;
                const steps = Math.max(1, start - end);
                const stepTime = Math.max(10, Math.floor(duration / steps));

                el.textContent = start;

                const timer = setInterval(() => {
                    start--;
                    el.textContent = start;
                    if (start <= end) clearInterval(timer);
                }, stepTime);
            });
        }

        /* ---------- Helpers ---------- */

        // Return {y,m,d} from "YYYY-MM-DD" or "YYYY-MM-DD HH:MM:SS". Invalid/zero dates -> null
        function getYMDParts(dateStr) {
            if (!dateStr) return null;
            const pure = String(dateStr).split(" ")[0]; // drop time if present
            const [y, m, d] = (pure || "").split("-").map(n => parseInt(n, 10));
            if (!y || !m || !d || y < 1900) return null;         // guard "0000-00-00" or invalid
            return { y, m, d };
        }

        // Get today in Asia/Manila at 00:00
        function getTodayPH() {
            const now = new Date();
            const ph = new Date(now.toLocaleString("en-US", { timeZone: "Asia/Manila" }));
            ph.setHours(0, 0, 0, 0);
            return { y: ph.getFullYear(), m: ph.getMonth() + 1, d: ph.getDate() };
        }

        // Returns ceil of (end - start) in days, and 0 if negative.
        function daysBetween(startYMD, endYMD) {
            const a = Date.UTC(startYMD.y, startYMD.m - 1, startYMD.d);
            const b = Date.UTC(endYMD.y, endYMD.m - 1, endYMD.d);
            const diff = Math.ceil((b - a) / 86400000);
            return Math.max(0, diff);
        }

        // Number of days in the given month (1-12)
        function daysInMonth(y, m) {
            return new Date(y, m, 0).getDate();
        }


        // =============================
        // Pagination
        // =============================
        function updateLogPagination(page, totalPages) {
            $("#pageInfo").text(`${page} of ${totalPages}`);
            $("#prevPage").prop("disabled", page <= 1);
            $("#nextPage").prop("disabled", page >= totalPages);
        }

        $("#prevPage").click(function () {
            if (currentLogPage > 1) {
                currentLogPage--;
                loadLogs($("#searchInput").val().trim());
            }
        });

        $("#nextPage").click(function () {
            if (currentLogPage < totalLogPages) {
                currentLogPage++;
                loadLogs($("#searchInput").val().trim());
            }
        });


        // =============================
        // Search
        // =============================
        $(document).on("keyup", "#searchInput", function () {
            currentLogPage = 1; // reset to page 1 when searching
            let keyword = $(this).val().trim();
            if (keyword.length === 0) {
                loadLogs(null);
            } else {
                loadLogs(keyword);
            }
        });


        // =============================
        // Sorting
        // =============================

        $(document).on("click", ".sort-btn", function () {
            const column = $(this).data("column");
            const isAsc = currentSort.column === column ? !currentSort.asc : true;
            currentSort = { column, asc: isAsc };

            // reset arrow classes
            $(".sort-btn").removeClass("asc desc");
            $(this).addClass(isAsc ? "asc" : "desc");

            logsData.sort((a, b) => {
                let valA, valB;
                switch (column) {
                    case "member":
                        valA = `${a.user_fname} ${a.user_lname}`.toLowerCase();
                        valB = `${b.user_fname} ${b.user_lname}`.toLowerCase();
                        break;
                    case "time_in":
                        valA = a.log_time_in ?? "";
                        valB = b.log_time_in ?? "";
                        break;
                    case "time_out":
                        valA = a.log_time_out ?? "";
                        valB = b.log_time_out ?? "";
                        break;
                    case "mem_type":
                        valA = a.mem_type ?? "";
                        valB = b.mem_type ?? "";
                        break;
                    default:
                        valA = "";
                        valB = "";
                }

                return isAsc ? valA.localeCompare(valB) : valB.localeCompare(valA);
            });

            renderLogs(logsData);
        });


        // =============================
        // RFID card logger
        // =============================

        function logRFID(tagId) {
            $.post("db/request.php", { action: "rfid_scan", user_rfid: tagId }, function (res) {
                let data = typeof res === "string" ? JSON.parse(res) : res;

                if (data.status === "success") {
                    if (data.user) {
                        // Fill modal
                        $("#modalUserImg").attr("src", "assets/images/user_image/" + data.user.user_image);
                        $("#modalUserName").text(data.user.name);
                        $("#modalFullName").text(data.user.name);
                        $("#modalUserGender").text(data.user.gender || "N/A");
                        $("#modalUserMobile").text(data.user.mobile || "N/A");
                        $("#modalUserEmail").text(data.user.email || "N/A");
                        $("#modalUserAddress").text(data.user.address || "N/A");

                        if (data.user.mem_type === "Monthly") {
                            $("#modalUserType").text("Monthly Member");
                            $("#modalSubscription").html("<strong>Subscription:</strong> " + data.user.mem_start_date + " - " + data.user.mem_end_date);
                        } else {
                            $("#modalUserType").text("Walk-in Member");
                            $("#modalSubscription").text("");
                        }

                        // Show modal
                        $("#userCardOverlay").fadeIn();

                        // Auto close after 10s
                        setTimeout(() => { $("#userCardOverlay").fadeOut(); }, 15000);
                    } else {
                        // Fallback: Swal for checkout success, etc.
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: data.message,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }

                    $("#manualId").val("");
                    let search = $("#searchInput").val().trim();
                    if (search) loadLogs(search);
                    else loadLogs();

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: data.message,
                        timer: 5000
                    });
                }
            });
        }

        // Close modal on overlay click
        function closeUserCard() {
            $("#userCardOverlay").fadeOut(function () {
                // Reset content
                $("#modalUserImg").attr("src", "");
                $("#modalUserName, #modalFullName, #modalUserGender, #modalUserMobile, #modalUserEmail, #modalUserAddress, #modalUserType, #modalSubscription").text("");
            });
        }

        // Auto close
        setTimeout(closeUserCard, 15000);

        // Close on overlay click
        $(document).on("click", "#userCardOverlay", function (e) {
            if (e.target.id === "userCardOverlay") {
                closeUserCard();
            }
        });



        // =============================
        // Switch between Scanner & Login
        // =============================
        document.addEventListener("DOMContentLoaded", () => {
            const scannerContainer = document.querySelector(".scanner-container");
            const loginContainer = document.querySelector(".login-container");
            const loginBtn = document.getElementById("loginBtn");
            const scanBtn = document.getElementById("scanBtn");

            loginBtn.addEventListener("click", () => {
                scannerContainer.style.display = "none";
                loginContainer.style.display = "flex";
                loginBtn.style.display = "none";
                scanBtn.style.display = "inline-block";
            });

            scanBtn.addEventListener("click", () => {
                scannerContainer.style.display = "flex";
                loginContainer.style.display = "none";
                scanBtn.style.display = "none";
                loginBtn.style.display = "inline-block";
            });


            // Focus RFID input always
            const searchInput = document.getElementById("searchInput");

            searchInput.addEventListener("focus", () => {
                // temporarily disable RFID auto-focus while user is typing in search
                document.removeEventListener("click", enforceFocus);
            });

            searchInput.addEventListener("blur", () => {
                // re-enable RFID auto-focus when user leaves search
                document.addEventListener("click", enforceFocus);
            });

            function enforceFocus(e) {
                if (e.target.id !== "searchInput") {
                    rfidInput.focus();
                }
            }
            document.addEventListener("click", enforceFocus);

            // Listen for RFID scans
            rfidInput.addEventListener("keypress", function (e) {
                if (e.key === "Enter") {
                    let tagId = rfidInput.value.trim();
                    if (tagId !== "") {
                        logRFID(tagId);
                        rfidInput.value = "";
                    }
                }
            });
        });

        // Expand table cell
        document.addEventListener("click", function (e) {
            if (e.target.tagName === "TD") {
                document.querySelectorAll("td.expanded").forEach(td => td.classList.remove("expanded"));
                e.target.classList.add("expanded");
            }
        });

    </script>

    <script type="text/javascript" src="assets/js/universal.js"></script>
</body>

</html>