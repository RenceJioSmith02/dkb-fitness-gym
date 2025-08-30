<?php
require "db/db.php";
$mydb = new myDB();

include_once 'partials/session.php'
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym MS | Member List</title>
    <link rel="icon" href="assets/images/system_image/favicon.png" type="image/png">
    <link rel="stylesheet" href="assets/css/main.css">
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script src="assets/js/sweetalert2.min.js"></script>
</head>

<body>

    <div id="overlay" style="display:none;"></div>

    <!-- Edit Form -->
    <div class="form-container modal scroller-format" id="edit-member">
        <div class="header flex jus-center al-center">
            <h2>Edit Member</h2>
        </div>

        <form method="POST" id="editMemberForm" enctype="multipart/form-data">
            <input type="hidden" name="user_id">

            <div class="form-input-container flex jus-center al-center flex-col">

                <div class="card-container flex al-center flex-row">

                    <div class="image-container flex jus-center al-center">
                        <div class="avatar">
                            <label for="imageUpload" class="image-upload-wrapper">
                                <img id="imagePreview" src="./assets/images/user_image/default.png"
                                    alt="Profile Preview">
                                <div class="upload-overlay">
                                    <img src="./assets/images/system_image/svg/camera.svg" alt="Camera Icon"
                                        class="fa-camera" style="width: 50px; height: 50px;" />
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="personal-info flex flexible al-center jus-center flex-col">

                        <div class="row1 al-center jus-center flex">
                            <div class="input-group">
                                <label>First Name:</label>
                                <input type="text" name="user_fname" required placeholder="Juan"><br>
                            </div>

                            <div class="input-group" style="max-width: 300px;">
                                <label>Middle Initial:</label>
                                <input type="text" name="user_mname" placeholder="P."><br>
                            </div>
                        </div>

                        <div class="row2 al-center jus-center flex">
                            <div class="input-group">
                                <label>Last Name:</label>
                                <input type="text" name="user_lname" required placeholder="Dela Cruz"><br>
                            </div>

                            <div class="input-group" style="max-width: 300px;">
                                <label>Suffix:</label>
                                <input type="text" name="user_suffix" placeholder="e.g. Jr (optional) "><br>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="add-info flex al-center just-center">

                    <div class="left-form-group">

                        <input type="file" id="imageUpload" name="user_image" accept="image/*">


                        <div class="input-group">
                            <label>Contact:</label>
                            <input type="text" name="user_contact" required placeholder="+639 063 162 634 "><br>
                        </div>

                        <div class="input-group">
                            <label>Email:</label>
                            <input type="email" name="user_email" required placeholder="juan@gmail.com"><br>
                        </div>

                        <div class="input-group">
                            <label>Address:</label>
                            <input type="text" name="user_address" required placeholder="Science City of Munoz"><br>
                        </div>

                        <div class="input-group">
                            <label>Gender:</label>
                            <select name="user_gender">
                                <option value="" disabled>-- Select Gender --</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select><br>
                        </div>

                    </div>


                    <div class="right-form-group">

                        <div class="input-group">
                            <label>Birthday:</label>
                            <input type="date" name="user_birthday" required><br>
                        </div>

                        <div class="form-group flex jus-center al-center">
                            <div class="input-group">
                                <label>Weight (kg):</label>
                                <input type="text" name="user_weight" required placeholder="0"><br>
                            </div>

                            <div class="input-group">
                                <label>Height (cm):</label>
                                <input type="text" name="user_height" required placeholder="0"><br>
                            </div>
                        </div>

                        <div class="form-group flex jus-center al-center">
                            <div class="input-group">
                                <label>Monthly Start:</label>
                                <input type="date" name="mem_start_date"><br>
                            </div>

                            <div class="input-group">
                                <label>Monthly End:</label>
                                <input type="date" name="mem_end_date"><br>
                            </div>
                        </div>

                        <div class="form-group flex jus-center al-center">
                            <div class="input-group">
                                <label>Membership Type:</label>
                                <select name="mem_type" required>
                                    <option value="" disabled>-- Select Membership Type --</option>
                                    <option value="Walk-in">Walk-in</option>
                                    <option value="Monthly">Monthly</option>
                                </select><br>
                            </div>

                            <div class="input-group">
                                <label>RFID:</label>
                                <input type="text" name="user_rfid" required placeholder="0123456789"><br>
                            </div>
                        </div>

                    </div>
                </div>


            </div>

            <div class="form-buttons">
                <input class="btn-primary" type="submit" name="update_member" value="Update" style="max-width: 80px;">
            </div>
        </form>
    </div>


    <!-- Add Form -->
    <div class="form-container modal scroller-format" id="add-member">
        <div class="header flex jus-center al-center">
            <h2>Add New Member</h2>
        </div>

        <form method="POST" id="addMemberForm" enctype="multipart/form-data">

            <div class="form-input-container flex jus-center al-center">
                <div class="left-form-group">
                    <div class="form-group flex jus-center al-center">
                        <div class="input-group">
                            <label>First Name:</label>
                            <input type="text" name="user_fname" required placeholder="Juan"><br>
                        </div>

                        <div class="input-group">
                            <label>Last Name:</label>
                            <input type="text" name="user_lname" required placeholder="Dela Cruz"><br>
                        </div>
                    </div>

                    <div class="form-group flex jus-center al-center">
                        <div class="input-group">
                            <label>MIddle Initial:</label>
                            <input type="text" name="user_mname" required placeholder="P."><br>
                        </div>

                        <div class="input-group">
                            <label>Suffix:</label>
                            <input type="text" name="user_suffix" placeholder="e.g. Jr (optional) "><br>
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Email:</label>
                        <input type="email" name="user_email" required placeholder="juan@gmail.com"><br>
                    </div>

                    <div class="input-group">
                        <label>Contact:</label>
                        <input type="text" name="user_contact" required placeholder="+639 063 162 634 "><br>
                    </div>

                </div>

                <div class="right-form-group">

                    <div class="input-group">
                        <label>Address:</label>
                        <input type="text" name="user_address" required placeholder="Science City of Munoz"><br>
                    </div>

                    <div class="form-group flex jus-center al-center">
                        <div class="input-group">
                            <label>Gender:</label>
                            <select name="user_gender" required>
                                <option value="" disabled>-- Select Gender --</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select><br>
                        </div>

                        <div class="input-group">
                            <label>Birthday:</label>
                            <input type="date" name="user_birthday" required><br>
                        </div>
                    </div>

                    <div class="form-group flex jus-cenetr al-center">
                        <div class="input-group">
                            <label>Weight (kg): </label>
                            <input type="text" name="user_weight" required placeholder="0"><br>
                        </div>
                        <div class="input-group">
                            <label>Height (cm):</label>
                            <input type="text" name="user_height" required placeholder="0"><br>
                        </div>
                    </div>

                    <div class="form-group flex jus-cenetr al-center">
                        <div class="input-group">
                            <label>Membership Type:</label>
                            <select name="mem_type" required>
                                <option value="" disabled>-- Select Membership Type --</option>
                                <option value="Walk-in">Walk-in</option>
                                <option value="Monthly">Monthly</option>
                            </select><br>
                        </div>

                        <div class="input-group">
                            <label>RFID:</label>
                            <input type="text" name="user_rfid" required placeholder="0123456789"><br>
                        </div>
                    </div>

                </div>
            </div>


            <!-- <label>Password:</label>
            <input type="password" name="auth_password" required><br> -->

            <div class="form-buttons">
                <input class="btn-primary" type="submit" name="add_member" value="Add" style="max-width: 80px;">
            </div>
        </form>
    </div>

    <div class="container flex flex-col jus-start al-center">

        <!-- Header -->
        <?php include_once 'partials/header.php' ?>

        <div class="content userspage">
            <div class="top-controls userspage flex jus-between al-center">
                <div class="search-container" style="order: 2;">
                    <input type="text" id="searchInput" placeholder="Search...">
                    <img src="./assets/images/system_image/svg/search-icon.svg" class="search-icon" />
                </div>

                <button class="btn-primary" style="max-width: 150px; order: 1;" id="showAddForm">+ Add Member</button>
            </div>

            <div class="table-container users">
                <table id="membersTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;"><button type="button" class="sort-btn" data-column="no">No.</button></th>
                            <th>Profile</th>
                            <th style="width: 120px;"><button type="button" class="sort-btn" data-column="id">Member ID
                                    <img src="./assets/images/system_image/svg/sorting-arrow.svg" class="sort-arrow" />
                                </button></th>
                            <th style="width: 250px;"><button type="button" class="sort-btn" data-column="name">Name
                                    <img src="./assets/images/system_image/svg/sorting-arrow.svg" class="sort-arrow" />
                                </button></th>
                            <th style="width: 120px;"><button type="button" class="sort-btn" data-column="height">Height (cm)
                                    <img src="./assets/images/system_image/svg/sorting-arrow.svg" class="sort-arrow" />
                                </button></th>
                            <th style="width: 120px;"><button type="button" class="sort-btn" data-column="weight">Weight (kg)
                                    <img src="./assets/images/system_image/svg/sorting-arrow.svg" class="sort-arrow" />
                                </button></th>
                            <th style="width: 120px;"><button type="button" class="sort-btn" data-column="birthday">Birthday
                                    <img src="./assets/images/system_image/svg/sorting-arrow.svg" class="sort-arrow" />
                                </button></th>
                            <th><button type="button" class="sort-btn" data-column="gender">Gender
                                    <img src="./assets/images/system_image/svg/sorting-arrow.svg" class="sort-arrow" />
                                </button></th>
                            <th><button type="button" class="sort-btn" data-column="address">Address
                                    <img src="./assets/images/system_image/svg/sorting-arrow.svg" class="sort-arrow" />
                                </button></th>
                            <th style="width: 120px;"><button type="button" class="sort-btn" data-column="contact">Contact
                                    <img src="./assets/images/system_image/svg/sorting-arrow.svg" class="sort-arrow" />
                                </button></th>
                            <th style="width: 250px;">Email</th>
                            <th style="width: 120px;"><button type="button" class="sort-btn" data-column="membership">Membership
                                    <img src="./assets/images/system_image/svg/sorting-arrow.svg" class="sort-arrow" />
                                </button></th>
                            <th><button type="button" class="sort-btn" data-column="status">Status
                                    <img src="./assets/images/system_image/svg/sorting-arrow.svg" class="sort-arrow" />
                                </button></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tBodyMember" class="scroller-format"></tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination-container" style="text-align:right;">
                    <button id="prevPage">&laquo; Prev</button>
                    <span id="pageInfo">1 of 1</span>
                    <button id="nextPage">Next &raquo;</button>
                </div>

            </div>

        </div>
    </div>


    <script type="text/javascript">
        let membersData = [];
        let currentSort = { column: null, asc: true };
        let currentPage = 1;
        let totalPages = 1;

        $(document).ready(function () {
            loadMembers();
        });


        // =============================
        // Load & Render Members
        // =============================
        function loadMembers(search = '') {
            $.ajax({
                url: "db/request.php",
                method: "POST",
                data: { action: "getMembers", search: search, page: currentPage },
                dataType: "json", // tell jQuery we expect JSON
                success: function (response) {
                    console.log(response);

                    if (response.status === "success") {
                        membersData = response.data;
                        totalPages = Math.ceil(response.total / response.limit);

                        applySorting(); // keep sorting
                        renderMembers(membersData);
                        updatePagination(response.page, totalPages);
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    alert("Something went wrong while loading members.");
                }
            });
        }

        function renderMembers(datas) {
            let tBody = ``;
            let cnt = (currentPage - 1) * 15 + 1;
            let currentYear = new Date().getFullYear();

            datas.forEach(function (data) {
                tBody += `<tr>`;
                tBody += `<td style="width: 50px;">${cnt++}</td>`;
                tBody += `<td><img src="assets/images/user_image/${data['user_image'] ?? 'default.png'}" 
                    width="40" height="40" style="border-radius:50%;"></td>`;
                tBody += `<td style="width: 120px;">${data['user_id']}-${currentYear}</td>`;
                tBody += `<td style="width: 250px;">${data['user_fname']} ${data['user_mname'] ?? ''}. ${data['user_lname']} ${data['user_suffix'] ?? ''}</td>`;
                tBody += `<td style="width: 120px;">${data['user_height'] ?? ''}cm</td>`;
                tBody += `<td style="width: 120px;">${data['user_weight'] ?? ''}kg</td>`;
                tBody += `<td style="width: 120px;">${data['user_birthday'] ?? ''}</td>`;
                tBody += `<td>${data['user_gender'] ?? ''}</td>`;
                tBody += `<td>${data['user_address'] ?? ''}</td>`;
                tBody += `<td style="width: 120px;">${data['user_contact'] ?? ''}</td>`;
                tBody += `<td style="width: 250px;">${data['user_email'] ?? ''}</td>`;
                tBody += `<td style="width: 120px;">${data['mem_type'] ?? ''}</td>`;
                tBody += `<td>${data['user_status'] ?? ''}</td>`;
                tBody += `<td>
                            <a href="#" class="btn-table-solid" onclick="editMember(${data['user_id']}); return false;">Edit</a>
                            <a href="#" class="btn-table-hollow" onclick="deleteMember(${data['user_id']}); return false;">Delete</a>
                        </td>`;
                tBody += `</tr>`;
            });

            if (datas.length === 0) {
                tBody = `<tr><td colspan="15" style="text-align:center;">No members found</td></tr>`;
            }

            $('#tBodyMember').html(tBody);
        }


        // =============================
        // Pagination
        // =============================
        function updatePagination(page, totalPages) {
            $("#pageInfo").text(`${page} of ${totalPages}`);
            $("#prevPage").prop("disabled", page <= 1);
            $("#nextPage").prop("disabled", page >= totalPages);
        }

        $("#prevPage").click(function () {
            if (currentPage > 1) {
                currentPage--;
                loadMembers($("#searchInput").val().trim());
            }
        });

        $("#nextPage").click(function () {
            if (currentPage < totalPages) {
                currentPage++;
                loadMembers($("#searchInput").val().trim());
            }
        });


        // =============================
        // Search
        // =============================
        $("#searchInput").on("input", function () {
            currentPage = 1; // reset to first page when searching
            const value = $(this).val().trim();
            loadMembers(value);
        });

        // =============================
        // Add Member
        // =============================
        $("#addMemberForm").on("submit", function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "addMember");

            $.ajax({
                url: "db/request.php",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {

                    if (res.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        $("#addMemberForm")[0].reset();
                        hideModals();
                        loadMembers($("#searchInput").val().trim());

                    } else {
                        Swal.fire("Error", res.message, "error");
                    }
                },
                error: function () {
                    console.log("Error:" + res.message);
                    Swal.fire("Error", "Something went wrong!", "error");
                }
            });
        });

        // =============================
        // Edit Member
        // =============================
        $("#editMemberForm").on("submit", function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "update_member");

            $.ajax({
                url: "db/request.php",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Updated",
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        $("#editMemberForm")[0].reset();
                        hideModals();
                        loadMembers($("#searchInput").val().trim());

                    } else {
                        Swal.fire("Error", response.message, "error");
                    }
                },
                error: function () {
                    Swal.fire("Error", "Update failed.", "error");
                }
            });
        });


        // =============================
        // Set every input value kapag nag e edit
        // =============================
        function editMember(id) {
            $.ajax({
                url: "db/request.php",
                method: "POST",
                dataType: "json",
                data: { action: "get_single_member", id: id },
                success: function (data) {
                    const form = $("#editMemberForm");

                    // fill form fields
                    form.find('input[name="user_id"]').val(data.user_id);
                    form.find('input[name="user_fname"]').val(data.user_fname);
                    form.find('input[name="user_mname"]').val(data.user_mname);
                    form.find('input[name="user_lname"]').val(data.user_lname);
                    form.find('input[name="user_suffix"]').val(data.user_suffix);
                    form.find('input[name="user_height"]').val(data.user_height);
                    form.find('input[name="user_weight"]').val(data.user_weight);
                    form.find('input[name="user_email"]').val(data.user_email);
                    form.find('input[name="user_contact"]').val(data.user_contact);
                    form.find('input[name="user_address"]').val(data.user_address);
                    form.find('input[name="user_birthday"]').val(data.user_birthday);
                    form.find('input[name="mem_start_date"]').val(data.mem_start_date);
                    form.find('input[name="mem_end_date"]').val(data.mem_end_date);
                    form.find('select[name="user_gender"]').val(data.user_gender);
                    form.find('select[name="mem_type"]').val(data.mem_type);
                    form.find('input[name="user_rfid"]').val(data.user_rfid);

                    // Handle profile image
                    if (data.user_image && data.user_image !== "default.png") {
                        imagePreview.src = "./assets/images/user_image/" + data.user_image;
                    } else {
                        imagePreview.src = DEFAULT_IMAGE;
                    }

                    showModal('edit-member');
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.log(xhr.responseText);
                    alert("Failed to fetch member data.");
                }
            });
        }


        // =============================
        // Delete Member
        // =============================
        function deleteMember(delete_id) {
            if (!confirm("Are you sure you want to delete this member?")) return;

            $.ajax({
                url: "db/request.php",
                method: "POST",
                dataType: "json", // ✅ expect JSON
                data: {
                    action: "delete_member", // ✅ must match PHP case
                    delete_id: delete_id
                },
                success: function (data) {
                    if (data.status === "success") {
                        loadMembers($("#searchInput").val().trim());
                    } else {
                        alert(data.message || "Failed to delete member.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.log(xhr.responseText);
                    alert("Something went wrong!");
                }
            });
        }


        // =============================
        // Sorting
        // =============================
        $(document).on("click", ".sort-btn", function () {
            const column = $(this).data("column");
            const isAsc = currentSort.column === column ? !currentSort.asc : true;
            currentSort = { column, asc: isAsc };

            $(".sort-btn").removeClass("asc");
            if (isAsc) $(this).addClass("asc");

            applySorting();
            renderMembers(membersData);
        });

        // safely convert mixed strings like "175 cm" or "70kg" to numbers
        function toNumber(val) {
            if (val === null || val === undefined) return NaN;
            if (typeof val === 'number') return val;
            const s = String(val).replace(/,/g, '').trim();
            const m = s.match(/-?\d+(\.\d+)?/);   // grab the first numeric token
            return m ? parseFloat(m[0]) : NaN;
        }

        function applySorting() {
            if (!currentSort.column) return;

            const { column, asc } = currentSort;
            const numericCols = new Set(["id", "height", "weight"]); // numeric columns

            membersData.sort((a, b) => {
                let valA, valB;

                switch (column) {
                    case "id":
                        valA = a.user_id ?? "";
                        valB = b.user_id ?? "";
                        break;
                    case "name":
                        valA = `${a.user_fname} ${a.user_lname}`;
                        valB = `${b.user_fname} ${b.user_lname}`;
                        break;
                    case "height":
                        valA = a.user_height ?? "";
                        valB = b.user_height ?? "";
                        break;
                    case "weight":
                        valA = a.user_weight ?? "";
                        valB = b.user_weight ?? "";
                        break;
                    case "birthday":
                        valA = a.user_birthday ?? "";
                        valB = b.user_birthday ?? "";
                        break;
                    case "gender":
                        valA = a.user_gender ?? "";
                        valB = b.user_gender ?? "";
                        break;
                    case "address":
                        valA = a.user_address ?? "";
                        valB = b.user_address ?? "";
                        break;
                    case "contact":
                        valA = a.user_contact ?? "";
                        valB = b.user_contact ?? "";
                        break;
                    case "email":
                        valA = a.auth_email ?? "";
                        valB = b.auth_email ?? "";
                        break;
                    case "membership":
                        valA = a.mem_type ?? "";
                        valB = b.mem_type ?? "";
                        break;
                    case "status":
                        valA = a.user_status ?? "";
                        valB = b.user_status ?? "";
                        break;
                    default:
                        return 0;
                }

                // numeric sort for specific columns
                if (numericCols.has(column)) {
                    const numA = toNumber(valA);
                    const numB = toNumber(valB);

                    if (!isNaN(numA) && !isNaN(numB)) {
                        return asc ? numA - numB : numB - numA;
                    }
                    // If one is number and the other is not, put the number first in ASC
                    if (!isNaN(numA) && isNaN(numB)) return asc ? -1 : 1;
                    if (isNaN(numA) && !isNaN(numB)) return asc ? 1 : -1;
                    // fall through to string compare 
                }

                // string compare fallback
                valA = valA.toString();
                valB = valB.toString();
                return asc
                    ? valA.localeCompare(valB, undefined, { numeric: true, sensitivity: "base" })
                    : valB.localeCompare(valA, undefined, { numeric: true, sensitivity: "base" });
            });
        }

        // =============================
        // Modals
        // =============================
        function showModal(id) {
            $('#overlay').show();
            $(`#${id}`).show();
        }

        function hideModals() {
            $('#overlay').hide();
            $('.modal').hide();
            imagePreview.src = DEFAULT_IMAGE;  // reset to default
            imageInput.value = "";             // clear file input
        }

        $("#showAddForm").on("click", function (e) {
            e.preventDefault();
            showModal('add-member');
        });

        $("#overlay").on("click", hideModals);


        // =============================
        // Image preview
        // =============================
        const imageInput = document.getElementById('imageUpload');
        const imagePreview = document.getElementById('imagePreview');

        const DEFAULT_IMAGE = "./assets/images/user_image/default.png";

        // --- File Upload Preview ---
        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = DEFAULT_IMAGE; // fallback if no file selected
            }
        });

        document.addEventListener("click", function (e) {
            if (e.target.tagName === "TD") {
                document.querySelectorAll("td.expanded").forEach(td => td.classList.remove("expanded"));
                e.target.classList.add("expanded");
            }
        });

    </script>

</body>


</html>