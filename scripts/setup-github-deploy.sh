#!/usr/bin/env bash
# One-time setup for GitHub Actions -> Namecheap production deploy.
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
KEY_DIR="$REPO_ROOT/scripts/.deploy-keys"
PRIVATE_KEY="$KEY_DIR/github_actions_deploy"
PUBLIC_KEY="$PRIVATE_KEY.pub"
MARKER="github-actions-prismaticpractice-deploy"

NAMECHEAP_HOST="${NAMECHEAP_HOST:-198.54.116.118}"
NAMECHEAP_PORT="${NAMECHEAP_PORT:-21098}"
NAMECHEAP_USER="${NAMECHEAP_USER:-jennebjj}"

echo "Setting up GitHub Actions deploy key..."

mkdir -p "$KEY_DIR"

if [ ! -f "$PRIVATE_KEY" ]; then
  ssh-keygen -t ed25519 -f "$PRIVATE_KEY" -N "" -C "$MARKER"
  echo "Created new deploy key at $PRIVATE_KEY"
else
  echo "Using existing deploy key at $PRIVATE_KEY"
fi

PUBKEY="$(cat "$PUBLIC_KEY")"

echo "Installing public key on Namecheap (if needed)..."
ssh -p "$NAMECHEAP_PORT" "${NAMECHEAP_USER}@${NAMECHEAP_HOST}" \
  "grep -Fq '$MARKER' ~/.ssh/authorized_keys 2>/dev/null || echo '$PUBKEY' >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys"

echo
echo "Verifying deploy key SSH access..."
ssh -i "$PRIVATE_KEY" -p "$NAMECHEAP_PORT" -o BatchMode=yes \
  "${NAMECHEAP_USER}@${NAMECHEAP_HOST}" "echo OK: deploy key authenticated"

echo
echo "============================================================"
echo "Add this GitHub repository secret:"
echo "  Name:  NAMECHEAP_SSH_PRIVATE_KEY"
echo "  Value: (full private key below)"
echo
echo "GitHub -> Settings -> Secrets and variables -> Actions -> New repository secret"
echo "Repo: https://github.com/jennyfan/prismaticminds/settings/secrets/actions"
echo "============================================================"
echo
cat "$PRIVATE_KEY"
echo
echo "After adding the secret, push this branch and merge to main."
echo "GitHub Actions will deploy automatically on every push to main."
