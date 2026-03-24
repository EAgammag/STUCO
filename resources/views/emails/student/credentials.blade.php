<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSU SCMS Account Credentials</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f3f3; font-family: Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f3f3f3; padding: 40px 20px;">
        <tr>
            <td align="center">

                {{-- ── Outer card ─────────────────────────────────────────── --}}
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.07); max-width: 100%;">

                    {{-- ── Header ─────────────────────────────────────────── --}}
                    <tr>
                        <td align="center" style="background-color: #590004; padding: 32px 24px; border-bottom: 4px solid #a50104;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: bold; letter-spacing: 1px;">Cagayan State University</h1>
                            <p style="color: #f3f3f3; margin: 6px 0 0 0; font-size: 13px; opacity: 0.85; letter-spacing: 0.5px;">Student Conduct Management System</p>
                        </td>
                    </tr>

                    {{-- ── Body ────────────────────────────────────────────── --}}
                    <tr>
                        <td style="padding: 40px 40px 30px 40px;">

                            <h2 style="color: #250001; margin: 0 0 18px 0; font-size: 20px; font-weight: bold;">Welcome, {{ $student->name }}</h2>

                            <p style="color: #4a5568; line-height: 1.7; margin: 0 0 24px 0; font-size: 15px;">
                                An official account has been provisioned for you in the CSU Student Conduct Management System (SCMS). This portal is used to track your academic standing, view disciplinary records, and submit appeals.
                            </p>

                            {{-- ── Credentials box ────────────────────────── --}}
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f3f3f3; border-radius: 6px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 22px 24px;">
                                        <p style="margin: 0 0 14px 0; font-size: 11px; color: #718096; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;">Your Login Credentials</p>

                                        <p style="margin: 0 0 10px 0; font-size: 15px; color: #250001;">
                                            <strong>Student ID:</strong>&nbsp;
                                            <span style="font-family: 'Courier New', Courier, monospace; background-color: #ffffff; padding: 3px 8px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 14px;">{{ $student->student_id }}</span>
                                        </p>

                                        <p style="margin: 0; font-size: 15px; color: #250001;">
                                            <strong>Temporary Password:</strong>&nbsp;
                                            <span style="font-family: 'Courier New', Courier, monospace; background-color: #ffffff; padding: 3px 8px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 14px;">{{ $password }}</span>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- ── Security notice ────────────────────────── --}}
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-left: 4px solid #a50104; background-color: #fff5f5; border-radius: 0 4px 4px 0; margin-bottom: 32px;">
                                <tr>
                                    <td style="padding: 14px 18px;">
                                        <p style="margin: 0 0 4px 0; font-size: 13px; color: #a50104; font-weight: bold;">Security Notice</p>
                                        <p style="margin: 0; font-size: 13px; color: #a50104; line-height: 1.5;">
                                            For your protection, you will be required to change this temporary password immediately upon your first login. Do not share these credentials with anyone.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- ── CTA button ──────────────────────────────── --}}
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('login') }}"
                                           style="display: inline-block; background-color: #590004; color: #ffffff; font-weight: bold; font-size: 15px; text-decoration: none; padding: 14px 32px; border-radius: 6px; border: 1px solid #250001; letter-spacing: 0.3px;">
                                            Access Your SCMS Portal
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- ── Footer ──────────────────────────────────────────── --}}
                    <tr>
                        <td align="center" style="padding: 24px 40px; border-top: 1px solid #e2e8f0; background-color: #fafafa;">
                            <p style="margin: 0 0 8px 0; font-size: 12px; color: #a0aec0; line-height: 1.5;">
                                This is an automated message generated by the Office of Student Development and Welfare.<br>
                                Please do not reply to this email.
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #a0aec0;">
                                &copy; {{ date('Y') }} Cagayan State University. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
                {{-- ── /Outer card ─────────────────────────────────────────── --}}

            </td>
        </tr>
    </table>

</body>
</html>
