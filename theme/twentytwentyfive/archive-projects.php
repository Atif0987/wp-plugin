<?php get_header(); ?>

<div class="container">
    <h1>Architecture Projects</h1>
    <div id="projects-container">Loading projects...</div>
    <div id="pagination"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    fetchProjects(1); 
});

function fetchProjects(page) {
    let ajaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";
    
    fetch(ajaxUrl + "?action=fetch_architecture_projects&page=" + page)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let output = "<ul>";
            data.data.forEach(project => {
                output += `<li><a href="${project.link}">${project.title}</a></li>`;
            });
            output += "</ul>";
            document.getElementById("projects-container").innerHTML = output;

            // Pagination buttons
            let paginationOutput = "";
            if (data.current_page > 1) {
                paginationOutput += `<button onclick="fetchProjects(${data.current_page - 1})">« Prev</button>`;
            }
            if (data.current_page < data.total_pages) {
                paginationOutput += `<button onclick="fetchProjects(${data.current_page + 1})">Next »</button>`;
            }
            document.getElementById("pagination").innerHTML = paginationOutput;
        } else {
            document.getElementById("projects-container").innerHTML = "<p>No projects found.</p>";
            document.getElementById("pagination").innerHTML = "";
        }
    })
    .catch(error => {
        console.error("Error fetching projects:", error);
        document.getElementById("projects-container").innerHTML = "<p>Error loading projects.</p>";
    });
}
</script>

<?php get_footer(); ?>
