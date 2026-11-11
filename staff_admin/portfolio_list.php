<?php
include("connection.php");
include("admin_functions.php");

// Check if user is logged in
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Get all portfolios for initial load
$result = mysqli_query($link, "SELECT * FROM portfolios ORDER BY submitted_at DESC");
$portfolios = [];
while ($row = mysqli_fetch_assoc($result)) {
    $portfolios[] = $row;
}

// Handle portfolio status update
if (isset($_GET['update_status'])) {
    $portfolio_id = $_GET['id'];
    $new_status = $_GET['update_status'];
    
    $update_sql = "UPDATE portfolios SET status = '$new_status' WHERE id = '$portfolio_id'";
    if (mysqli_query($link, $update_sql)) {
        header('Location: portfolio_list.php?success=Portfolio status updated successfully');
        exit();
    } else {
        header('Location: portfolio_list.php?error=Error updating portfolio status');
        exit();
    }
}

// Handle download requests
if (isset($_GET['download'])) {
    $portfolio_id = $_GET['id'];
    $format = $_GET['format'];
    
    // Fetch portfolio data
    $portfolio_query = "SELECT * FROM portfolios WHERE id = '$portfolio_id'";
    $portfolio_result = mysqli_query($link, $portfolio_query);
    $portfolio = mysqli_fetch_assoc($portfolio_result);
    
    if ($portfolio) {
        // Fetch education data
        $education_query = "SELECT * FROM portfolio_education WHERE portfolio_id = '$portfolio_id'";
        $education_result = mysqli_query($link, $education_query);
        $education = [];
        while ($edu_row = mysqli_fetch_assoc($education_result)) {
            $education[] = $edu_row;
        }
        
        // Fetch certificates
        $certificates_query = "SELECT * FROM portfolio_certificates WHERE portfolio_id = '$portfolio_id'";
        $certificates_result = mysqli_query($link, $certificates_query);
        $certificates = [];
        while ($cert_row = mysqli_fetch_assoc($certificates_result)) {
            $certificates[] = $cert_row;
        }
        
        switch($format) {
            case 'pdf':
                downloadAsPDF($portfolio, $education, $certificates);
                break;
            case 'doc':
                downloadAsDOC($portfolio, $education, $certificates);
                break;
        }
    }
}

// Function to download as PDF
function downloadAsPDF($portfolio, $education, $certificates) {
    // For now, we'll create a simple PDF download
    // You can integrate with libraries like TCPDF, mPDF, or Dompdf later
    
    $filename = "portfolio_" . $portfolio['id'] . "_" . $portfolio['full_name'] . ".pdf";
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // Simple PDF content - in real implementation, use a PDF library
    $content = "PORTFOLIO DETAILS\n";
    $content .= "================\n\n";
    $content .= "Personal Information:\n";
    $content .= "Full Name: " . $portfolio['full_name'] . "\n";
    $content .= "Email: " . $portfolio['email'] . "\n";
    $content .= "Phone: +" . $portfolio['country_code'] . " " . $portfolio['phone_number'] . "\n";
    $content .= "Location: " . $portfolio['current_location'] . "\n\n";
    
    $content .= "Professional Information:\n";
    $content .= "Designation: " . $portfolio['current_designation'] . "\n";
    $content .= "Experience: " . $portfolio['total_experience'] . " years\n";
    $content .= "Skills: " . $portfolio['selected_skills'] . "\n\n";
    
    // For now, output as text - you'll need to implement proper PDF generation
    echo $content;
    exit();
}

// Function to download as DOC
function downloadAsDOC($portfolio, $education, $certificates) {
    $filename = "portfolio_" . $portfolio['id'] . "_" . $portfolio['full_name'] . ".doc";
    
    header('Content-Type: application/vnd.ms-word');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // Create Word document content
    $content = "<html xmlns:o='urn:schemas-microsoft-com:office:office' 
                xmlns:w='urn:schemas-microsoft-com:office:word' 
                xmlns='http://www.w3.org/TR/REC-html40'>
                <head>
                <meta charset='utf-8'>
                <title>Portfolio: " . $portfolio['full_name'] . "</title>
                </head>
                <body>
                <h1>PORTFOLIO DETAILS</h1>
                <h2>Personal Information</h2>
                <table border='1'>
                <tr><td><strong>Full Name:</strong></td><td>" . $portfolio['full_name'] . "</td></tr>
                <tr><td><strong>Email:</strong></td><td>" . $portfolio['email'] . "</td></tr>
                <tr><td><strong>Phone:</strong></td><td>+" . $portfolio['country_code'] . " " . $portfolio['phone_number'] . "</td></tr>
                <tr><td><strong>Location:</strong></td><td>" . $portfolio['current_location'] . "</td></tr>
                <tr><td><strong>Visa Status:</strong></td><td>" . $portfolio['visa_status'] . "</td></tr>
                </table>
                
                <h2>Professional Information</h2>
                <table border='1'>
                <tr><td><strong>Designation:</strong></td><td>" . $portfolio['current_designation'] . "</td></tr>
                <tr><td><strong>Experience:</strong></td><td>" . $portfolio['total_experience'] . " years</td></tr>
                <tr><td><strong>Skills:</strong></td><td>" . $portfolio['selected_skills'] . "</td></tr>
                <tr><td><strong>Current Salary:</strong></td><td>" . $portfolio['current_salary'] . " " . $portfolio['salary_currency'] . "</td></tr>
                </table>
                
                <h2>Technical Strengths</h2>
                <p>" . nl2br($portfolio['technical_strengths']) . "</p>
                
                <h2>Education</h2>";
    
    foreach ($education as $edu) {
        $content .= "<p><strong>" . $edu['degree_type'] . "</strong> in " . $edu['specialization'] . 
                   " from " . $edu['university_name'] . " (" . $edu['graduation_year'] . ")</p>";
    }
    
    $content .= "</body></html>";
    
    echo $content;
    exit();
}

// Helper function to get profile type text
function getProfileTypeText($type) {
    $types = [
        '1' => 'International Professional',
        '2' => 'Global Professional',
        '3' => 'International Student'
    ];
    return $types[$type] ?? 'Unknown';
}

// Function to check if file exists and get correct path
function getFilePath($file_path) {
    if (empty($file_path)) {
        return null;
    }
    
    // Remove any leading slashes or dots
    $clean_path = ltrim($file_path, './');
    
    // Check if file exists in uploads directory
    $possible_paths = [
        $clean_path,
        'uploads/portfolios/' . basename($clean_path),
        '../uploads/portfolios/' . basename($clean_path),
        '../../uploads/portfolios/' . basename($clean_path)
    ];
    
    foreach ($possible_paths as $path) {
        if (file_exists($path) && is_file($path)) {
            return $path;
        }
    }
    
    return null;
}
?>

<?php include 'header.php'; ?>

<div class="page-content">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Portfolio Management</h4>
                <div>
                    <span class="badge bg-primary" id="totalCount">Total Portfolios: <?php echo count($portfolios); ?></span>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <!-- Real-time Search and Filter Section -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class='bx bx-search'></i> Real-time Search & Filter</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchInput" placeholder="Search by any field...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Filter By</label>
                            <select class="form-select" id="columnFilter">
                                <option value="">All Columns</option>
                                <option value="name">Name</option>
                                <option value="email">Email</option>
                                <option value="designation">Designation</option>
                                <option value="skills">Skills</option>
                                <option value="location">Location</option>
                                <option value="industry">Industry</option>
                                <option value="category">Category</option>
                                <option value="experience">Experience</option>
                                <option value="status">Status</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Profile Type</label>
                            <select class="form-select" id="profileTypeFilter">
                                <option value="">All Types</option>
                                <option value="1">International Professional</option>
                                <option value="2">Global Professional</option>
                                <option value="3">International Student</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="submitted">Submitted</option>
                                <option value="under_review">Under Review</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Industry</label>
                            <select class="form-select" id="industryFilter">
                                <option value="">All Industries</option>
                                <option value="CS">Computer Science</option>
                                <option value="IT">Information Technology</option>
                                <option value="ENG">Engineering</option>
                                <!-- Add more industries as needed -->
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-secondary" id="clearFilters">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Profile Type</th>
                            <th>Industry</th>
                            <th>Skills</th>
                            <th>Location</th>
                            <th>Experience</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="portfolioTableBody">
                        <?php if (empty($portfolios)): ?>
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class='bx bx-briefcase-alt text-muted' style="font-size: 3rem;"></i>
                                    <p class="text-muted mb-0">No portfolio submissions found</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($portfolios as $portfolio): 
                                // Get education details for this portfolio
                                $education_query = "SELECT * FROM portfolio_education WHERE portfolio_id = '{$portfolio['id']}'";
                                $education_result = mysqli_query($link, $education_query);
                                $education = [];
                                while ($edu_row = mysqli_fetch_assoc($education_result)) {
                                    $education[] = $edu_row;
                                }
                                
                                // Get actual file paths
                                $resume_file_path = getFilePath($portfolio['resume_path']);
                                $portfolio_file_path = getFilePath($portfolio['portfolio_path']);
                                
                                // Parse JSON fields
                                $selected_skills = $portfolio['selected_skills'] ? json_decode($portfolio['selected_skills'], true) : [];
                                $us_experience = $portfolio['us_experience'] ? json_decode($portfolio['us_experience'], true) : [];
                                $india_experience = $portfolio['india_experience'] ? json_decode($portfolio['india_experience'], true) : [];
                                $location_preference = $portfolio['location_preference'] ? json_decode($portfolio['location_preference'], true) : [];
                            ?>
                            <tr class="portfolio-row" 
                                data-id="<?php echo $portfolio['id']; ?>"
                                data-name="<?php echo htmlspecialchars(strtolower($portfolio['full_name'])); ?>"
                                data-email="<?php echo htmlspecialchars(strtolower($portfolio['email'])); ?>"
                                data-designation="<?php echo htmlspecialchars(strtolower($portfolio['current_designation'])); ?>"
                                data-skills="<?php echo htmlspecialchars(strtolower($portfolio['selected_skills'] . ' ' . $portfolio['technical_strengths'])); ?>"
                                data-industry="<?php echo htmlspecialchars(strtolower($portfolio['core_industry'])); ?>"
                                data-category="<?php echo htmlspecialchars(strtolower($portfolio['category'])); ?>"
                                data-profile-type="<?php echo $portfolio['profile_type']; ?>"
                                data-status="<?php echo $portfolio['status']; ?>"
                                data-location="<?php echo htmlspecialchars(strtolower($portfolio['current_location'])); ?>"
                                data-experience="<?php echo $portfolio['total_experience']; ?>">
                                <td><?php echo $portfolio['id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-light-warning rounded me-2">
                                            <i class='bx bx-briefcase'></i>
                                        </div>
                                        <div>
                                            <strong><?php echo htmlspecialchars($portfolio['full_name']); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars($portfolio['current_designation']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($portfolio['email']); ?></td>
                                <td>
                                    <?php 
                                    $profile_types = [
                                        '1' => 'International Professional',
                                        '2' => 'Global Professional', 
                                        '3' => 'International Student'
                                    ];
                                    echo $profile_types[$portfolio['profile_type']] ?? 'Unknown';
                                    ?>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars($portfolio['core_industry'] ?: 'Not specified'); ?></small>
                                    <?php if ($portfolio['category']): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($portfolio['category']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($selected_skills) && is_array($selected_skills)): ?>
                                        <?php foreach (array_slice($selected_skills, 0, 3) as $skill): ?>
                                            <span class="badge bg-light text-dark mb-1"><?php echo htmlspecialchars($skill); ?></span>
                                        <?php endforeach; ?>
                                        <?php if (count($selected_skills) > 3): ?>
                                            <small class="text-muted">+<?php echo count($selected_skills) - 3; ?> more</small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <small class="text-muted">No skills listed</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars($portfolio['current_location']); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo $portfolio['total_experience']; ?> yrs</span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php 
                                        switch($portfolio['status']) {
                                            case 'pending': echo 'warning'; break;
                                            case 'submitted': echo 'primary'; break;
                                            case 'approved': echo 'success'; break;
                                            case 'rejected': echo 'danger'; break;
                                            case 'under_review': echo 'info'; break;
                                            default: echo 'secondary';
                                        }
                                    ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $portfolio['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <small><?php echo date('M j, Y', strtotime($portfolio['submitted_at'])); ?></small>
                                    <br>
                                    <small class="text-muted"><?php echo date('h:i A', strtotime($portfolio['submitted_at'])); ?></small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info btn-sm view-portfolio-btn" 
                                                data-portfolio-id="<?php echo $portfolio['id']; ?>"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#portfolioModal">
                                            <i class='bx bx-show'></i> View
                                        </button>
                                        <div class="dropdown">
                                            <button class="btn btn-warning btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class='bx bx-cog'></i> Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><h6 class="dropdown-header">Status</h6></li>
                                                <li><a class="dropdown-item" href="portfolio_list.php?id=<?php echo $portfolio['id']; ?>&update_status=pending">Mark as Pending</a></li>
                                                <li><a class="dropdown-item" href="portfolio_list.php?id=<?php echo $portfolio['id']; ?>&update_status=submitted">Mark as Submitted</a></li>
                                                <li><a class="dropdown-item" href="portfolio_list.php?id=<?php echo $portfolio['id']; ?>&update_status=under_review">Mark Under Review</a></li>
                                                <li><a class="dropdown-item" href="portfolio_list.php?id=<?php echo $portfolio['id']; ?>&update_status=approved">Approve</a></li>
                                                <li><a class="dropdown-item" href="portfolio_list.php?id=<?php echo $portfolio['id']; ?>&update_status=rejected">Reject</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><h6 class="dropdown-header">Download</h6></li>
                                                <li><a class="dropdown-item download-btn" href="portfolio_list.php?id=<?php echo $portfolio['id']; ?>&download=pdf&format=pdf"><i class='bx bx-download'></i> Download as PDF</a></li>
                                                <li><a class="dropdown-item download-btn" href="portfolio_list.php?id=<?php echo $portfolio['id']; ?>&download=doc&format=doc"><i class='bx bx-download'></i> Download as DOC</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><h6 class="dropdown-header">Resume</h6></li>
                                                <li>
                                                    <?php if ($resume_file_path): ?>
                                                        <a class="dropdown-item view-resume-btn" href="#" data-resume-path="<?php echo htmlspecialchars($resume_file_path); ?>" data-portfolio-name="<?php echo htmlspecialchars($portfolio['full_name']); ?>">
                                                            <i class='bx bx-file'></i> View Resume
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="dropdown-item text-muted"><i class='bx bx-file'></i> No Resume</span>
                                                    <?php endif; ?>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="view_portfolio.php?id=<?php echo $portfolio['id']; ?>" target="_blank"><i class='bx bx-link-external'></i> Open Detailed View</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Single Dynamic Modal for All Portfolios -->
<div class="modal fade" id="portfolioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class='bx bx-briefcase me-2'></i>
                    Portfolio Details - <span id="modalUserName"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="portfolioModalBody">
                <!-- Content will be loaded via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <div class="btn-group" id="modalActionButtons">
                    <!-- Action buttons will be loaded via JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resume View Modal -->
<div class="modal fade" id="resumeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class='bx bx-file me-2'></i>
                    Resume - <span id="resumeUserName"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="resumeModalBody">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading resume...</span>
                    </div>
                    <p class="mt-2">Loading resume...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-primary" id="downloadResumeBtn" download>
                    <i class='bx bx-download'></i> Download Resume
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Portfolio data for JavaScript access
const portfolioData = {
    <?php foreach ($portfolios as $portfolio): 
        $education_query = "SELECT * FROM portfolio_education WHERE portfolio_id = '{$portfolio['id']}'";
        $education_result = mysqli_query($link, $education_query);
        $education = [];
        while ($edu_row = mysqli_fetch_assoc($education_result)) {
            $education[] = $edu_row;
        }
        
        // Parse JSON fields
        $selected_skills = $portfolio['selected_skills'] ? json_decode($portfolio['selected_skills'], true) : [];
        $us_experience = $portfolio['us_experience'] ? json_decode($portfolio['us_experience'], true) : [];
        $india_experience = $portfolio['india_experience'] ? json_decode($portfolio['india_experience'], true) : [];
        $location_preference = $portfolio['location_preference'] ? json_decode($portfolio['location_preference'], true) : [];
        
        // Handle location preference (could be array or string)
        if (is_array($location_preference)) {
            $location_preference_display = implode(', ', $location_preference);
        } else {
            $location_preference_display = $portfolio['location_preference'];
        }
        
        // Get actual file paths
        $resume_file_path = getFilePath($portfolio['resume_path']);
        $portfolio_file_path = getFilePath($portfolio['portfolio_path']);
    ?>
    "<?php echo $portfolio['id']; ?>": {
        id: "<?php echo $portfolio['id']; ?>",
        full_name: "<?php echo htmlspecialchars($portfolio['full_name']); ?>",
        email: "<?php echo htmlspecialchars($portfolio['email']); ?>",
        phone: "+<?php echo htmlspecialchars($portfolio['country_code']); ?> <?php echo htmlspecialchars($portfolio['phone_number']); ?>",
        current_location: "<?php echo htmlspecialchars($portfolio['current_location']); ?>",
        visa_status: "<?php echo htmlspecialchars($portfolio['visa_status'] ?: 'Not specified'); ?>",
        linkedin_profile: "<?php echo htmlspecialchars($portfolio['linkedin_profile'] ?: ''); ?>",
        languages_spoken: "<?php echo htmlspecialchars($portfolio['languages_spoken']); ?>",
        citizenship_status: "<?php echo htmlspecialchars($portfolio['citizenship_status']); ?>",
        profile_type: "<?php echo $portfolio['profile_type']; ?>",
        total_experience: "<?php echo $portfolio['total_experience']; ?>",
        current_designation: "<?php echo htmlspecialchars($portfolio['current_designation']); ?>",
        functions_desired: "<?php echo htmlspecialchars($portfolio['functions_desired']); ?>",
        current_salary: "<?php echo $portfolio['current_salary']; ?>",
        salary_currency: "<?php echo $portfolio['salary_currency']; ?>",
        location_preference: "<?php echo htmlspecialchars($location_preference_display); ?>",
        willing_relocate: "<?php echo htmlspecialchars($portfolio['willing_relocate']); ?>",
        availability: "<?php echo htmlspecialchars($portfolio['availability']); ?>",
        technical_strengths: `<?php echo addslashes($portfolio['technical_strengths']); ?>`,
        personal_strengths: `<?php echo addslashes($portfolio['personal_strengths']); ?>`,
        return_purpose: "<?php echo htmlspecialchars($portfolio['return_purpose']); ?>",
        additional_info: `<?php echo addslashes($portfolio['additional_info'] ?: 'No additional information provided'); ?>`,
        resume_path: "<?php echo htmlspecialchars($portfolio['resume_path'] ?: ''); ?>",
        portfolio_path: "<?php echo htmlspecialchars($portfolio['portfolio_path'] ?: ''); ?>",
        resume_file_path: "<?php echo htmlspecialchars($resume_file_path ?: ''); ?>",
        portfolio_file_path: "<?php echo htmlspecialchars($portfolio_file_path ?: ''); ?>",
        status: "<?php echo $portfolio['status']; ?>",
        core_industry: "<?php echo htmlspecialchars($portfolio['core_industry'] ?: 'Not specified'); ?>",
        category: "<?php echo htmlspecialchars($portfolio['category'] ?: 'Not specified'); ?>",
        subcategory: "<?php echo htmlspecialchars($portfolio['subcategory'] ?: 'Not specified'); ?>",
        selected_skills: <?php echo json_encode($selected_skills); ?>,
        project_management_exp: "<?php echo $portfolio['project_management_exp'] ?: 'no'; ?>",
        project_management_skills: "<?php echo htmlspecialchars($portfolio['project_management_skills'] ?: 'Not specified'); ?>",
        cross_industry_exp: "<?php echo $portfolio['cross_industry_exp'] ?: 'no'; ?>",
        us_experience: <?php echo json_encode($us_experience); ?>,
        india_experience: <?php echo json_encode($india_experience); ?>,
        education: <?php echo json_encode($education); ?>,
        profile_types: {
            '1': 'International Professional',
            '2': 'Global Professional', 
            '3': 'International Student'
        }
    },
    <?php endforeach; ?>
};

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const columnFilter = document.getElementById('columnFilter');
    const profileTypeFilter = document.getElementById('profileTypeFilter');
    const statusFilter = document.getElementById('statusFilter');
    const industryFilter = document.getElementById('industryFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const portfolioTableBody = document.getElementById('portfolioTableBody');
    const totalCount = document.getElementById('totalCount');
    const viewPortfolioBtns = document.querySelectorAll('.view-portfolio-btn');
    const viewResumeBtns = document.querySelectorAll('.view-resume-btn');
    const downloadBtns = document.querySelectorAll('.download-btn');
    const portfolioModal = document.getElementById('portfolioModal');
    const resumeModal = document.getElementById('resumeModal');
    const modalUserName = document.getElementById('modalUserName');
    const resumeUserName = document.getElementById('resumeUserName');
    const portfolioModalBody = document.getElementById('portfolioModalBody');
    const resumeModalBody = document.getElementById('resumeModalBody');
    const modalActionButtons = document.getElementById('modalActionButtons');
    const downloadResumeBtn = document.getElementById('downloadResumeBtn');
    
    const portfolioRows = document.querySelectorAll('.portfolio-row');
    
    // Function to filter portfolios in real-time
    function filterPortfolios() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const column = columnFilter.value;
        const profileType = profileTypeFilter.value;
        const status = statusFilter.value;
        const industry = industryFilter.value.toLowerCase().trim();
        
        let visibleCount = 0;
        
        portfolioRows.forEach(row => {
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            const designation = row.getAttribute('data-designation');
            const skills = row.getAttribute('data-skills');
            const industryData = row.getAttribute('data-industry');
            const category = row.getAttribute('data-category');
            const rowProfileType = row.getAttribute('data-profile-type');
            const rowStatus = row.getAttribute('data-status');
            const rowLocation = row.getAttribute('data-location');
            const experience = row.getAttribute('data-experience');
            
            let matchesSearch = false;
            
            if (!searchTerm) {
                matchesSearch = true;
            } else if (!column || column === 'name') {
                matchesSearch = name.includes(searchTerm);
            } else if (column === 'email') {
                matchesSearch = email.includes(searchTerm);
            } else if (column === 'designation') {
                matchesSearch = designation.includes(searchTerm);
            } else if (column === 'skills') {
                matchesSearch = skills.includes(searchTerm);
            } else if (column === 'location') {
                matchesSearch = rowLocation.includes(searchTerm);
            } else if (column === 'industry') {
                matchesSearch = industryData.includes(searchTerm) || category.includes(searchTerm);
            } else if (column === 'category') {
                matchesSearch = category.includes(searchTerm);
            } else if (column === 'experience') {
                matchesSearch = experience.includes(searchTerm);
            } else if (column === 'status') {
                matchesSearch = rowStatus.includes(searchTerm);
            } else {
                // Search all fields
                matchesSearch = name.includes(searchTerm) || 
                              email.includes(searchTerm) || 
                              designation.includes(searchTerm) || 
                              skills.includes(searchTerm) ||
                              industryData.includes(searchTerm) ||
                              category.includes(searchTerm) ||
                              rowLocation.includes(searchTerm);
            }
            
            // Check filters
            const matchesProfileType = !profileType || rowProfileType === profileType;
            const matchesStatus = !status || rowStatus === status;
            const matchesIndustry = !industry || industryData.includes(industry) || category.includes(industry);
            
            // Show/hide row based on all conditions
            if (matchesSearch && matchesProfileType && matchesStatus && matchesIndustry) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update total count
        totalCount.textContent = `Showing: ${visibleCount} of ${portfolioRows.length} portfolios`;
        
        // Show no results message if no rows are visible
        if (visibleCount === 0) {
            if (!document.getElementById('noResultsRow')) {
                const noResultsRow = document.createElement('tr');
                noResultsRow.id = 'noResultsRow';
                noResultsRow.innerHTML = `
                    <td colspan="11" class="text-center py-4">
                        <i class='bx bx-search text-muted' style="font-size: 3rem;"></i>
                        <p class="text-muted mb-0">No portfolios match your search criteria</p>
                        <small class="text-muted">Try adjusting your search terms or filters</small>
                    </td>
                `;
                portfolioTableBody.appendChild(noResultsRow);
            }
        } else {
            const noResultsRow = document.getElementById('noResultsRow');
            if (noResultsRow) {
                noResultsRow.remove();
            }
        }
    }
    
    // Function to load portfolio data into modal
    function loadPortfolioModal(portfolioId) {
        const portfolio = portfolioData[portfolioId];
        if (!portfolio) return;
        
        // Set modal title
        modalUserName.textContent = portfolio.full_name;
        
        // Generate education HTML
        let educationHTML = '';
        if (portfolio.education && portfolio.education.length > 0) {
            portfolio.education.forEach(edu => {
                educationHTML += `
                    <div class="row border-bottom py-2">
                        <div class="col-md-3"><strong>${edu.degree_type}</strong></div>
                        <div class="col-md-3">${edu.specialization || 'Not specified'}</div>
                        <div class="col-md-4">${edu.university_name}</div>
                        <div class="col-md-2">${edu.graduation_year}</div>
                    </div>
                `;
            });
        } else {
            educationHTML = '<p class="text-muted">No education information provided</p>';
        }
        
        // Generate skills HTML
        let skillsHTML = '';
        if (portfolio.selected_skills && portfolio.selected_skills.length > 0) {
            portfolio.selected_skills.forEach(skill => {
                skillsHTML += `<span class="badge bg-light text-dark me-1 mb-1">${skill}</span>`;
            });
        } else {
            skillsHTML = '<p class="text-muted">No skills specified</p>';
        }
        
        // Generate experience HTML
        let usExperienceHTML = '';
        if (portfolio.us_experience && portfolio.us_experience.length > 0) {
            portfolio.us_experience.forEach(exp => {
                usExperienceHTML += `
                    <div class="row border-bottom py-2">
                        <div class="col-md-4"><strong>${exp.employer || 'Not specified'}</strong></div>
                        <div class="col-md-3">${exp.from || ''} - ${exp.to || ''}</div>
                        <div class="col-md-3">${exp.city || ''}, ${exp.state || ''}</div>
                    </div>
                `;
            });
        } else {
            usExperienceHTML = '<p class="text-muted">No US experience specified</p>';
        }
        
        let indiaExperienceHTML = '';
        if (portfolio.india_experience && portfolio.india_experience.length > 0) {
            portfolio.india_experience.forEach(exp => {
                indiaExperienceHTML += `
                    <div class="row border-bottom py-2">
                        <div class="col-md-4"><strong>${exp.employer || 'Not specified'}</strong></div>
                        <div class="col-md-3">${exp.from || ''} - ${exp.to || ''}</div>
                        <div class="col-md-3">${exp.city || ''}, ${exp.state || ''}</div>
                    </div>
                `;
            });
        } else {
            indiaExperienceHTML = '<p class="text-muted">No India experience specified</p>';
        }
        
        // Generate file links HTML
        let filesHTML = '';
        if (portfolio.resume_file_path) {
            const resumeFilename = portfolio.resume_path.split('/').pop();
            filesHTML += `
                <div class="mb-2">
                    <strong>Resume:</strong> 
                    <span class="text-muted">${resumeFilename}</span>
                    <button class="btn btn-sm btn-outline-primary ms-2 view-resume-modal-btn" 
                            data-resume-path="${portfolio.resume_file_path}" 
                            data-portfolio-name="${portfolio.full_name}">
                        <i class='bx bx-show'></i> View Resume
                    </button>
                </div>
            `;
        } else if (portfolio.resume_path) {
            const resumeFilename = portfolio.resume_path.split('/').pop();
            filesHTML += `
                <div class="mb-2">
                    <strong>Resume:</strong> 
                    <span class="text-muted">${resumeFilename}</span>
                    <span class="badge bg-warning ms-2">File not found at expected location</span>
                </div>
            `;
        }
        
        if (portfolio.portfolio_file_path) {
            const portfolioFilename = portfolio.portfolio_path.split('/').pop();
            filesHTML += `
                <div class="mb-2">
                    <strong>Portfolio:</strong> 
                    <span class="text-muted">${portfolioFilename}</span>
                    <a href="${portfolio.portfolio_file_path}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                        <i class='bx bx-show'></i> View File
                    </a>
                </div>
            `;
        } else if (portfolio.portfolio_path) {
            const portfolioFilename = portfolio.portfolio_path.split('/').pop();
            filesHTML += `
                <div class="mb-2">
                    <strong>Portfolio:</strong> 
                    <span class="text-muted">${portfolioFilename}</span>
                    <span class="badge bg-warning ms-2">File not found at expected location</span>
                </div>
            `;
        }
        
        if (!filesHTML) {
            filesHTML = '<p class="text-muted">No files uploaded</p>';
        }
        
        // Set modal body content with all new fields
        portfolioModalBody.innerHTML = `
            <div class="row">
                <!-- Personal Information -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class='bx bx-user me-2'></i>Personal Information</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm">
                                <tr><td class="fw-bold">Full Name:</td><td>${portfolio.full_name}</td></tr>
                                <tr><td class="fw-bold">Email:</td><td>${portfolio.email}</td></tr>
                                <tr><td class="fw-bold">Phone:</td><td>${portfolio.phone}</td></tr>
                                <tr><td class="fw-bold">Current Location:</td><td>${portfolio.current_location}</td></tr>
                                <tr><td class="fw-bold">Visa Status:</td><td>${portfolio.visa_status}</td></tr>
                                <tr><td class="fw-bold">LinkedIn:</td><td>${portfolio.linkedin_profile ? `<a href="${portfolio.linkedin_profile}" target="_blank">View Profile</a>` : 'Not provided'}</td></tr>
                                <tr><td class="fw-bold">Languages:</td><td>${portfolio.languages_spoken}</td></tr>
                                <tr><td class="fw-bold">Citizenship:</td><td>${portfolio.citizenship_status}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class='bx bx-briefcase me-2'></i>Professional Information</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm">
                                <tr><td class="fw-bold">Profile Type:</td><td>${portfolio.profile_types[portfolio.profile_type] || 'Unknown'}</td></tr>
                                <tr><td class="fw-bold">Experience:</td><td>${portfolio.total_experience} years</td></tr>
                                <tr><td class="fw-bold">Current Designation:</td><td>${portfolio.current_designation}</td></tr>
                                <tr><td class="fw-bold">Desired Function:</td><td>${portfolio.functions_desired}</td></tr>
                                <tr><td class="fw-bold">Current Salary:</td><td>${portfolio.current_salary} ${portfolio.salary_currency}</td></tr>
                                <tr><td class="fw-bold">Location Preference:</td><td>${portfolio.location_preference}</td></tr>
                                <tr><td class="fw-bold">Willing to Relocate:</td><td>${portfolio.willing_relocate}</td></tr>
                                <tr><td class="fw-bold">Availability:</td><td>${portfolio.availability}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <!-- Industry & Skills -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class='bx bx-category me-2'></i>Industry & Skills</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm">
                                <tr><td class="fw-bold">Core Industry:</td><td>${portfolio.core_industry}</td></tr>
                                <tr><td class="fw-bold">Category:</td><td>${portfolio.category}</td></tr>
                                <tr><td class="fw-bold">Subcategory:</td><td>${portfolio.subcategory}</td></tr>
                                <tr><td class="fw-bold">Project Management Exp:</td><td>${portfolio.project_management_exp === 'yes' ? 'Yes' : 'No'}</td></tr>
                                <tr><td class="fw-bold">Cross Industry Exp:</td><td>${portfolio.cross_industry_exp === 'yes' ? 'Yes' : 'No'}</td></tr>
                            </table>
                            <strong>Selected Skills:</strong>
                            <div class="mt-2">${skillsHTML}</div>
                            ${portfolio.project_management_skills && portfolio.project_management_skills !== 'Not specified' ? `
                                <strong class="mt-3 d-block">Project Management Skills:</strong>
                                <p>${portfolio.project_management_skills}</p>
                            ` : ''}
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class='bx bx-code-block me-2'></i>Technical Expertise</h6>
                        </div>
                        <div class="card-body">
                            <p>${portfolio.technical_strengths.replace(/\n/g, '<br>')}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class='bx bx-user-voice me-2'></i>Professional Strengths</h6>
                        </div>
                        <div class="card-body">
                            <p>${portfolio.personal_strengths.replace(/\n/g, '<br>')}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class='bx bx-note me-2'></i>Additional Information</h6>
                        </div>
                        <div class="card-body">
                            <p>${portfolio.additional_info.replace(/\n/g, '<br>')}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Experience Section -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class='bx bx-buildings me-2'></i>US Experience</h6>
                        </div>
                        <div class="card-body">
                            <div class="row border-bottom py-2 fw-bold">
                                <div class="col-md-4">Employer</div>
                                <div class="col-md-3">Duration</div>
                                <div class="col-md-3">Location</div>
                            </div>
                            ${usExperienceHTML}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class='bx bx-buildings me-2'></i>India Experience</h6>
                        </div>
                        <div class="card-body">
                            <div class="row border-bottom py-2 fw-bold">
                                <div class="col-md-4">Employer</div>
                                <div class="col-md-3">Duration</div>
                                <div class="col-md-3">Location</div>
                            </div>
                            ${indiaExperienceHTML}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class='bx bx-target-lock me-2'></i>Career Objective</h6>
                </div>
                <div class="card-body">
                    <p>${portfolio.return_purpose}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class='bx bx-graduation me-2'></i>Educational Background</h6>
                </div>
                <div class="card-body">
                    <div class="row border-bottom py-2 fw-bold">
                        <div class="col-md-3">Degree</div>
                        <div class="col-md-3">Specialization</div>
                        <div class="col-md-4">University</div>
                        <div class="col-md-2">Year</div>
                    </div>
                    ${educationHTML}
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class='bx bx-file me-2'></i>File Attachments</h6>
                </div>
                <div class="card-body">
                    ${filesHTML}
                </div>
            </div>
        `;
        
        // Set action buttons with download options
        modalActionButtons.innerHTML = `
            <div class="btn-group">
                <a href="portfolio_list.php?id=${portfolio.id}&update_status=approved" class="btn btn-success">Approve</a>
                <a href="portfolio_list.php?id=${portfolio.id}&update_status=rejected" class="btn btn-danger">Reject</a>
            </div>
            <div class="btn-group ms-2">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class='bx bx-download'></i> Download
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="portfolio_list.php?id=${portfolio.id}&download=pdf&format=pdf"><i class='bx bx-file'></i> PDF</a></li>
                    <li><a class="dropdown-item" href="portfolio_list.php?id=${portfolio.id}&download=doc&format=doc"><i class='bx bx-file'></i> DOC</a></li>
                </ul>
            </div>
        `;
        
        // Add event listeners for resume view buttons in modal
        document.querySelectorAll('.view-resume-modal-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const resumePath = this.getAttribute('data-resume-path');
                const portfolioName = this.getAttribute('data-portfolio-name');
                viewResume(resumePath, portfolioName);
            });
        });
    }
    
    // Function to view resume in modal
    function viewResume(resumePath, portfolioName) {
        resumeUserName.textContent = portfolioName;
        downloadResumeBtn.href = resumePath;
        downloadResumeBtn.download = `resume_${portfolioName.replace(/\s+/g, '_')}.pdf`;
        
        // Show loading state
        resumeModalBody.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading resume...</span>
                </div>
                <p class="mt-2">Loading resume...</p>
            </div>
        `;
        
        // Show the modal
        const resumeModalInstance = new bootstrap.Modal(resumeModal);
        resumeModalInstance.show();
        
        // Load the resume content (for PDF files, we'll embed them)
        setTimeout(() => {
            const fileExtension = resumePath.split('.').pop().toLowerCase();
            
            if (fileExtension === 'pdf') {
                // Embed PDF
                resumeModalBody.innerHTML = `
                    <div class="embed-responsive embed-responsive-16by9">
                        <embed src="${resumePath}" type="application/pdf" width="100%" height="600px" />
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class='bx bx-info-circle'></i> If the PDF is not displaying, you can <a href="${resumePath}" target="_blank">open it in a new tab</a> or download it using the button below.
                    </div>
                `;
            } else {
                // For other file types, show download option
                resumeModalBody.innerHTML = `
                    <div class="text-center">
                        <i class='bx bx-file text-muted' style="font-size: 4rem;"></i>
                        <h5 class="mt-3">Resume File</h5>
                        <p class="text-muted">This file type cannot be previewed in the browser.</p>
                        <p><strong>File:</strong> ${resumePath.split('/').pop()}</p>
                        <a href="${resumePath}" class="btn btn-primary" download>
                            <i class='bx bx-download'></i> Download Resume
                        </a>
                    </div>
                `;
            }
        }, 1000);
    }
    
    // Event listeners for view buttons
    viewPortfolioBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const portfolioId = this.getAttribute('data-portfolio-id');
            loadPortfolioModal(portfolioId);
        });
    });
    
    // Event listeners for resume view buttons in dropdown
    viewResumeBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const resumePath = this.getAttribute('data-resume-path');
            const portfolioName = this.getAttribute('data-portfolio-name');
            viewResume(resumePath, portfolioName);
        });
    });
    
    // Event listeners for real-time filtering
    searchInput.addEventListener('input', filterPortfolios);
    columnFilter.addEventListener('change', filterPortfolios);
    profileTypeFilter.addEventListener('change', filterPortfolios);
    statusFilter.addEventListener('change', filterPortfolios);
    industryFilter.addEventListener('change', filterPortfolios);
    
    // Clear filters functionality
    clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        columnFilter.value = '';
        profileTypeFilter.value = '';
        statusFilter.value = '';
        industryFilter.value = '';
        filterPortfolios();
    });
    
    // Debounce function for search input to improve performance
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterPortfolios, 300);
    });
    
    // Initialize filter on page load
    filterPortfolios();
});
</script>

<?php include 'footer.php'; ?>