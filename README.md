## Nginx+PHP-FPM Deployment

- Requires Ansible 2.2.1.0 or newer
- Expects CentOS/RHEL 6.x hosts

These playbooks deploy a simple all-in-one configuration of the popular
WordPress blogging platform and CMS, frontend by the Nginx web server and the
PHP-FPM process manager. To use, copy the `hosts.example` file to `hosts` and 
edit the `hosts` inventory file to include the names or URLs of the servers
you want to deploy.

Then run the playbook, like this:

	ansible-playbook -i hosts site.yml

The playbooks will configure MySQL, Nginx, and PHP-FPM...

### Ideas for Improvement

Here are some ideas for ways that these playbooks could be extended:

- Separate the components (PHP-FPM, MySQL, Nginx) onto separate hosts and 
handle the configuration appropriately.

### More example see
- https://github.com/ansible/ansible-examples

We would love to see contributions and improvements, so please fork this
repository on GitHub and send us your changes via pull requests.
