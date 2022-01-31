
.PHONY: wiki
wiki:
	docker build -t tuxemon/semantic-mediawiki:latest ./wiki

upgrade:
	docker build -t tuxemon/semantic-mediawiki-update:latest -f ./wiki/Dockerfile.upgrade ./wiki
