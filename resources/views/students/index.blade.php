<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance</title>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
    <style>
        body { padding: 20px; }
        table { width: 100%; }
        .modal-title { font-weight: bold; }
        .modal-body label { font-weight: bold; }
    </style>
</head>
<body>

    <div class="container">
        <h1 class="text-center">Student Attendance Management</h1>
        <button id="createNewStudent" class="btn btn-success my-3">Create New Student</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date of Birth</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="studentsTable">
                <!-- Students Data will be displayed here -->
            </tbody>
        </table>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="studentForm">
                        <input type="hidden" id="studentId">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" class="form-control" required>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="on_duty">On Duty</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
    <script>
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $(document).ready(function () {
            fetchStudents();

            // Fetch students and populate table
            function fetchStudents() {
                $.get('/students', function (data) {
                    let rows = '';
                    data.students.forEach(function (student) {
                        rows += `
                            <tr>
                                <td>${student.name}</td>
                                <td>${student.email}</td>
                                <td>${student.date_of_birth}</td>
                                <td>${student.status}</td>
                                <td>
                                    <button class="btn btn-primary editStudent" data-id="${student.id}">Edit</button>
                                    <button class="btn btn-danger deleteStudent" data-id="${student.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#studentsTable').html(rows);
                });
            }

            // Show create modal
            $('#createNewStudent').click(function () {
                $('#studentForm')[0].reset();
                $('#studentId').val('');
                $('#studentModalLabel').text('Create New Student');
                $('#studentModal').modal('show');
            });

            // Show edit modal
            $(document).on('click', '.editStudent', function () {
                const id = $(this).data('id');
                $.get(`/students/${id}/edit`, function (data) {
                    $('#studentModalLabel').text('Edit Student');
                    $('#studentId').val(data.student.id);
                    $('#name').val(data.student.name);
                    $('#email').val(data.student.email);
                    $('#date_of_birth').val(data.student.date_of_birth);
                    $('#status').val(data.student.status);
                    $('#studentModal').modal('show');
                });
            });

            // Save or update student
            $('#studentForm').submit(function (e) {
                e.preventDefault();
                const id = $('#studentId').val();
                const url = id ? `/students/${id}` : '/students';
                const type = id ? 'PUT' : 'POST';
                $.ajax({
                    url: url,
                    type: type,
                    data: {
                        name: $('#name').val(),
                        email: $('#email').val(),
                        date_of_birth: $('#date_of_birth').val(),
                        status: $('#status').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        $('#studentModal').modal('hide');
                        fetchStudents();
                    }
                });
            });

            // Delete student
            $(document).on('click', '.deleteStudent', function () {
                const id = $(this).data('id');
                $.ajax({
                    url: `/students/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        fetchStudents();
                    }
                });
            });
        });
    </script>
</body>
</html>

</body>
</html>