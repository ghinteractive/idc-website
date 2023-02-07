{{/*
Expand the name of the chart.
*/}}
{{- define "bedrock-project.name" -}}
{{- default .Chart.Name .Values.nameOverride | trunc 63 | trimSuffix "-" }}
{{- end }}

{{/*
Create a default fully qualified app name.
We truncate at 63 chars because some Kubernetes name fields are limited to this (by the DNS naming spec).
If release name contains chart name it will be used as a full name.
*/}}
{{- define "bedrock-project.fullname" -}}
{{- if .Values.fullnameOverride }}
{{- .Values.fullnameOverride | trunc 63 | trimSuffix "-" }}
{{- else }}
{{- $name := default .Chart.Name .Values.nameOverride }}
{{- if contains $name .Release.Name }}
{{- .Release.Name | trunc 63 | trimSuffix "-" }}
{{- else }}
{{- printf "%s-%s" .Release.Name $name | trunc 63 | trimSuffix "-" }}
{{- end }}
{{- end }}
{{- end }}

{{/*
Create chart name and version as used by the chart label.
*/}}
{{- define "bedrock-project.chart" -}}
{{- printf "%s-%s" .Chart.Name .Chart.Version | replace "+" "_" | trunc 63 | trimSuffix "-" }}
{{- end }}

{{/*
Common labels
*/}}
{{- define "bedrock-project.labels" -}}
helm.sh/chart: {{ include "bedrock-project.chart" . }}
{{ include "bedrock-project.selectorLabels" . }}
{{- if .Chart.AppVersion }}
app.kubernetes.io/version: {{ .Chart.AppVersion | quote }}
{{- end }}
app.kubernetes.io/managed-by: {{ .Release.Service }}
{{- end }}

{{/*
Selector labels
*/}}
{{- define "bedrock-project.selectorLabels" -}}
app.kubernetes.io/name: {{ include "bedrock-project.name" . }}
app.kubernetes.io/instance: {{ .Release.Name }}
{{- end }}

{{/*
Node Affinity
*/}}
{{- define "bedrock-project.nodeAffinity" -}}
affinity:
{{- if .Values.affinity }}
    {{- toYaml .Values.affinity }}
{{- else }}
  nodeAffinity:
    requiredDuringSchedulingIgnoredDuringExecution:
      nodeSelectorTerms:
        - matchExpressions:
          - key: gh/audience
            operator: In
            values:
              - client
          - key: gh/subnet
            operator: In
            values:
              - private
    preferredDuringSchedulingIgnoredDuringExecution:
      - weight: 1
        preference:
          matchExpressions:
            - key: gh/environment
              operator: In
              values:
                - {{ .Release.Namespace }}
  podAntiAffinity:
    preferredDuringSchedulingIgnoredDuringExecution:
      - weight: 100
        podAffinityTerm:
          topologyKey: kubernetes.io/hostname
          labelSelector:
            matchExpressions:
              - key: app.kubernetes.io/deployment
                operator: In
                values:
                  - {{ include "bedrock-project.fullname" . }}-deploy
{{- end }}
{{- end }}

{{/*
Create the name of the service account to use
*/}}
{{- define "bedrock-project.serviceAccountName" -}}
{{- if .Values.serviceAccount.create }}
{{- default (include "bedrock-project.fullname" .) .Values.serviceAccount.name }}
{{- else }}
{{- default "default" .Values.serviceAccount.name }}
{{- end }}
{{- end }}

{{/*
Full URL Path of the website
*/}}
{{- define "bedrock-project.homeurl" -}}
{{- if .Values.ingress.tls -}}
{{- print "https://" .Values.hostName -}}
{{- else }}
{{- print "http://" .Values.hostName -}}
{{- end }}
{{- end }}

{{/*
Container Image name
*/}}
{{- define "bedrock-project.imageName" -}}
{{- printf "%s:%s" .repository (.tag | default "latest") -}}
{{- end }}

{{/*
Get ElasticSearch Host
*/}}
{{- define "bedrock-project.elasticsearch.host" -}}
{{- $protocol := ternary "https" "http" .Values.elasticsearch.https.enabled }}
{{- print $protocol "://" .Values.elasticsearch.service.name ":" .Values.elasticsearch.service.port -}}
{{- end }}

{{/*
Get ElasticSearch Autocomplete endpoint
*/}}
{{- define "bedrock-project.elasticsearch.endpoint" -}}
{{- if gt (.Values.elasticsearch.autocomplete.indices | len) 0 }}
{{- $indices := printf (join "," .Values.elasticsearch.autocomplete.indices) .Release.Name .Release.Namespace }}
{{- print $indices "/" .Values.elasticsearch.autocomplete.endpoint.dest -}}
{{- else }}
{{- print .Values.elasticsearch.autocomplete.endpoint.dest }}
{{- end }}
{{- end }}