# Commands

Installing the plugin will introduce a new composer command group: **patch**

```shell
# Apply new patches (similar to patch apply on 'composer install') 
composer patch:apply

# Re-apply all patches
composer patch:redo 

# Re-apply all patches and ignore any local repository changes on packages
composer patch:redo --force

# Re-apply patches for one speicif package
# (Keeps the patches that were not matched, applied)
composer patch:redo my/package 

# Re-apply all patches except patches declared against my/package
# (Keeps the patches that were not matched, applied)
composer patch:redo '!my/package'

# Re-apply patches for one specific package with patch name filter 
# (Keeps the patches that were not matched, applied)
composer patch:redo --filter wrong-time-format --filter other-file 

# Re-apply patches and skip filenames that contain 'some<anything>description'  
# (Keeps the patches that were not matched, applied)
composer patch:redo --filter '!some*description' my/package 

# Reset all patched packages
composer patch:undo 

# Reset one specific patched package
composer patch:undo my/package 

# Reset one specific patch on package
composer patch:undo --filter some-fix my/package

# Reset packages that have patch that contains 'some-fix' in it's path definition
composer patch:undo --filter some-fix

# Gather patches information from /vendor instead of install.json
composer patch:apply --from-source

# Ideal for testing out a newly added patch against my/package
composer patch:redo --from-source

# Check that all patches have valid target package info (using info from vendor)
composer patch:validate --from-source  

# Check that patches that are owner by ROOT package are all defined correctly
composer patch:validate --local

# List registered with their current status (with optional filter(s))
copmposer patch:list --status new --status removed

# List all patches that have either changed, are new or got removed 
composer patch:list --status '!applied'

```

The main purpose of this command is to make the maintenance of already created patches and adding new 
ones as easy as possible by allowing user to test out a patch directly right after defining it without 
having to trigger 'composer update' or 'composer install'.