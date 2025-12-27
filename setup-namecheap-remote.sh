#!/bin/bash
# Setup script to run on namecheap server via SSH
# This configures the repository to accept pushes to a checked-out branch

echo "Setting up namecheap repository for pushes..."

# Navigate to the repository
cd ~/prismaticpractice.com

# Allow pushes to checked-out branch
git config receive.denyCurrentBranch ignore

# Create post-receive hook to update working tree automatically
cat > .git/hooks/post-receive << 'EOF'
#!/bin/bash
# Post-receive hook to update working tree after push
cd ~/prismaticpractice.com
git checkout -f main
EOF

# Make the hook executable
chmod +x .git/hooks/post-receive

echo "Setup complete!"
echo "You can now push from your local machine with: git push namecheap main"

