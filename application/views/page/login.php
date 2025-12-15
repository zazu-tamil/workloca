<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Trekking Application</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --forest-dark: #0d2417;
            --forest-green: #0c94c1ff;
            --forest-light: #0c94c1ff;
            --accent: #f6c46d;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                linear-gradient(rgba(10, 27, 20, 0.48), rgba(10, 27, 20, 0.44)),
                url("asset/images/dawn-9856375_1280.jpg") center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: "Poppins", sans-serif;
        }

        .mountain-bg {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 140px;
            background: url("https://i.imgur.com/wWXf5Rh.png") repeat-x bottom;
            opacity: 0.22;
            pointer-events: none;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(14px);
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.55);
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, #0c94c142, #0c94c161);
            color: #fff;
            padding: 1.8rem 1.3rem;
            text-align: center;
        }

        .card-header h3 {
            margin: 8px 0 0;
            font-weight: 700;
            font-size: 1.55rem;
        }

        .card-body {
            padding: 1.8rem;
            color: #d9e9df;
        }

        .form-label {
            font-weight: 600;
            color: #eaf4ee;
        }

        .form-control {
            background: rgb(255 255 255 / 86%);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #000;
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.18rem rgba(245, 196, 109, 0.3);
        }

        .password-toggle {
            cursor: pointer;
            color: #eee;
            background: rgba(255, 255, 255, 0.12);
            border-left: 0 !important;
        }

        .password-toggle:hover {
            color: var(--accent);
        }

        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #095e72, #095e72);
            border: none;
            padding: 0.9rem;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: 0.2s;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.45);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, var(--forest-light), var(--forest-green));
        }
    </style>
</head>

<body>
    <div class="mountain-bg"></div>

    <div class="login-card">
        <div class="card-header">
            <i class="bi bi-mountains fs-2"></i>
            <h3><?php echo PG_HEAD; ?></h3>
            <p class="mt-1">Access your Worklokha dashboard</p>
        </div>

        <div class="card-body">

            <form action="<?php echo site_url('login'); ?>" method="post" novalidate>

                <div class="mb-3">
                    <label class="form-label">Username or Email</label>
                    <input type="text" name="user_name" class="form-control" placeholder="Enter username or email"
                        value="<?php echo set_value('user_name'); ?>" required>
                    <?php echo form_error('user_name', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="user_pwd" id="user_pwd" class="form-control"
                            placeholder="Enter password" required>
                        <span class="input-group-text password-toggle" id="togglePassword">
                            <i class="bi bi-eye-slash" id="eyeIcon"></i>
                        </span>
                    </div>
                    <?php echo form_error('user_pwd', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <button type="submit" class="btn btn-primary mt-2">Login</button>
            </form>
        </div>
    </div>

    <!-- Custom Toast Notification Script (Top-Right, Beautiful Design) -->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            background: '#0c94c1a6',
            color: '#ffffff',
            padding: '1rem 1.5rem',
            customClass: {
                popup: 'colored-toast',
                title: 'toast-title',
                icon: 'toast-icon'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Success Toast (after login success)
        <?php if ($this->session->flashdata('login_success')): ?>
            Toast.fire({
                icon: 'success',
                title: 'Welcome back!',
                text: '<?php echo $this->session->flashdata('login_success'); ?>'
            }).then(() => {
                window.location = '<?php echo site_url("dash"); ?>';
            });
        <?php endif; ?>

        // Error Toast
        <?php if (isset($msg)): ?>
            Toast.fire({
                icon: 'error',
                title: 'Login Failed',
                text: '<?php echo addslashes($msg); ?>'
            });
        <?php endif; ?>
    </script>

    <style>
        div:where(.swal2-icon).swal2-success {
            border-color: #ffffffff !important;
            color: #ffffffff !important;
        } 
    </style>

    <!-- Password Toggle -->
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const pwd = document.getElementById('user_pwd');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                pwd.type = 'password';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });
    </script>

    <!-- Optional: Extra styling for toast (rounded + glow) -->
    <style>
        .colored-toast {
            border-radius: 12px !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.6) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(79, 138, 90, 0.3);
        }

        .toast-title {
            font-weight: 600 !important;
            font-size: 1.1rem !important;
        }

        .toast-icon {
            font-size: 1.8rem !important;
        }
    </style>

</body>

</html>